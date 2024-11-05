import {SelectControl} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import {useState} from 'react';

export function ModeSelector({
  config, onChange = (mode) => {
  },
}) {
  const [mode, setMode] = useState(config.mode);
  const [firstMonth, setFirstMonth] = useState(config.firstMonth);

  function handleModeChange(value) {
    setMode(value);
    onChange({mode: value, firstMonth});
  }

  function handleMonthModeSelect(value) {
    setFirstMonth(value);
    onChange({mode, firstMonth: value});
  }

  const monthSelector = mode === 'month'
    ?
    <SelectControl
      label="fist month"
      value={firstMonth}
      help="Select the first month to display"
      options={[
        {label: __('Current month', 'arcw'), value: 'current'},
        {label: __('Latest month', 'arcw'), value: 'latest'},
        {label: __('Next month', 'arcw'), value: 'next'},
        {label: __('Previous month', 'arcw'), value: 'previous'},
      ]}
      selected={firstMonth}
      onChange={handleMonthModeSelect}
    />
    : '';

  return <>
    <SelectControl
      label="Display mode"
      value={mode}
      options={[
        {label: __('Month', 'arcw'), value: 'month'},
        {label: __('Year', 'arcw'), value: 'year'},
      ]}
      selected={mode}
      onChange={handleModeChange}
    />
    {monthSelector}
  </>;
}
