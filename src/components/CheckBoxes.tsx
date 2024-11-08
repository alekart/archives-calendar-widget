import { CheckboxControl } from '@wordpress/components';
import { useState } from 'react';
import { CheckboxOption, CheckboxOptionValue } from '../interfaces';
import { addUniqueToArray, removeFromArray } from '../utils/array-utils';
import { __ } from '@wordpress/i18n';
import { noop } from '../utils/noop';

/**
 * If provided list is empty and if autoValue is provided a new list with the provided value will be returned.
 * In all other cases a new list with the same content will be returned.
 */
function addProvidedIfListEmpty(selection: CheckboxOptionValue[] = [], autoValue?: CheckboxOptionValue) {
  return autoValue && selection.length === 0
    ? [autoValue]
    : [...selection];
}

interface CheckboxesAttributes {
  checkboxes: CheckboxOption[];
  selected?: CheckboxOptionValue[];
  autoSelectIfNone?: CheckboxOptionValue;
  displaySelectAll?: boolean;
  onSelectChange?: (values: CheckboxOptionValue[]) => void;
}

export function CheckBoxes({
  checkboxes = [], selected = [], autoSelectIfNone, displaySelectAll, onSelectChange = noop,
}: CheckboxesAttributes) {
  const [selectedValues, setSelectedValues] = useState<CheckboxOptionValue[]>(addProvidedIfListEmpty(selected, autoSelectIfNone));

  function isSelected(value: CheckboxOptionValue) {
    return selectedValues.includes(value);
  }

  function isAllSelected() {
    return checkboxes.length === selectedValues.length;
  }

  function handleChecked(value: CheckboxOptionValue, checked: boolean) {
    setSelectedValues((prevState) => {
      let updated = checked
        ? addUniqueToArray(prevState, value)
        : removeFromArray(prevState, value);
      updated = addProvidedIfListEmpty(updated, autoSelectIfNone);
      onSelectChange(updated);
      return updated;
    });
  }

  function handleSelectAll() {
    const selected = isAllSelected()
      ? addProvidedIfListEmpty([], autoSelectIfNone)
      : checkboxes.map(({value}) => value);
    setSelectedValues(selected);
    onSelectChange(selected);
  }

  return <>
    {displaySelectAll
      ? <CheckboxControl
        key="arcw-select-all"
        label={__('All', 'arcw')}
        checked={isAllSelected()}
        onChange={handleSelectAll}
      />
      : ''
    }
    {checkboxes.map((item) => (
      <CheckboxControl
        key={item.value}
        label={item.label}
        checked={isSelected(item.value)}
        onChange={(checked) => handleChecked(item.value, checked)}
      />
    ))}
  </>;
}
