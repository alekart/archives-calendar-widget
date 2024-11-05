import {CheckboxControl} from '@wordpress/components';
import {useState} from 'react';
import {addUniqueToArray, removeFromArray} from '../utils/array-utils';
import {__} from '@wordpress/i18n';

/**
 * If provided list is empty and if autoValue is provided a new list with the provided value will be returned.
 * In all other cases a new list with the same content will be returned.
 * @param selection
 * @param autoValue
 * @returns {*[]}
 */
function addProvidedIfListEmpty(selection = [], autoValue) {
  return autoValue && selection.length === 0
    ? [autoValue]
    : [...selection];
}

export function CheckBoxes({
  checkboxes = [], selected = [], autoSelectIfNone, displaySelectAll, onChange: onSelectChange = () => {
  },
}) {
  const [selectedValues, setSelectedValues] = useState(addProvidedIfListEmpty(selected, autoSelectIfNone));

  function isSelected(value) {
    return selectedValues.includes(value);
  }

  function isAllSelected() {
    return checkboxes.length === selectedValues.length;
  }

  function handleChecked(value, checked) {
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
      ? setSelectedValues(addProvidedIfListEmpty([], autoSelectIfNone))
      : setSelectedValues(checkboxes.map(({value}) => value));
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
