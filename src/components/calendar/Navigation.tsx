import { __ } from '@wordpress/i18n';
import { orderBy } from 'lodash';
import { ChangeEvent, useMemo, useState } from 'react';
import { Modes } from '../../enums';
import { noop } from '../../utils/noop';

const monthNames: string[] = [
  'january',
  'february',
  'march',
  'april',
  'may',
  'june',
  'july',
  'august',
  'september',
  'october',
  'november',
  'december',
].map((m) => __(m))

interface NavMonth {
  month?: number;
  year: number;
}

interface NavMonthWithLabel extends NavMonth {
  label: string;
}

interface Props {
  options: NavMonth[];
  active: NavMonth;
  mode: Modes;
  onChange: (selection: NavMonth) => void
}

export function Navigation({options, active, mode, onChange = noop}: Props) {
  const [selected, setSelected] = useState(active);

  const opts: NavMonthWithLabel[] = useMemo(() => {
    return orderBy(options.map((opt) => ({
      ...opt,
      label: getLabel(opt),
    })), (item) => `${item.year}${item.month}`);
  }, [options]);

  function handleSelect(event: ChangeEvent<HTMLSelectElement>) {
    const selectedOption = parseInt(event.target.value, 10);
    const selectedMonth = options[selectedOption];
    setSelected(selectedMonth);
    onChange(selectedMonth);
  }

  function isSelected(value: NavMonth): boolean {
    return selected.month === value.month && selected.year === value.year;
  }

  function getLabel(value: NavMonth) {
    if (mode === Modes.Year) {
      return `${value.year}`;
    } else {
      return `${monthNames[value.month || 0]} ${value.year}`;
    }
  }

  return <div>
    <button type="button">{__('Prev', 'arcw')}</button>
    <select onChange={handleSelect}>
      {opts.map((opt, index) => (
        <option key={opt.label} value={index} selected={isSelected(opt)}>{opt.label}</option>
      ))}
    </select>
    <button type="button">{__('Next', 'arcw')}</button>
  </div>
}
