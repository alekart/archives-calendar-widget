import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState } from 'react';
import { Modes, MonthConfig } from '../enums';
import { ArcwConfig, ArcwModeConfig } from '../interfaces';
import { noop } from '../utils/noop';

const monthOptions = [
  {label: __('Current month', 'arcw'), value: MonthConfig.Current},
  {label: __('Latest month', 'arcw'), value: MonthConfig.Latest},
  {label: __('Next month', 'arcw'), value: MonthConfig.Next},
  {label: __('Previous month', 'arcw'), value: MonthConfig.Previous},
]

const modeOptions = [
  {label: __('Month', 'arcw'), value: Modes.Month},
  {label: __('Year', 'arcw'), value: Modes.Year},
]

export function ModeSelector({config, onChange = noop}: {
  config: ArcwConfig,
  onChange: (config: ArcwModeConfig) => void
}) {
  const [mode, setMode] = useState<Modes>(config.mode);
  const [firstMonth, setFirstMonth] = useState<MonthConfig>(config.firstMonth);

  function handleModeChange(value: Modes) {
    setMode(value);
    onChange({mode: value, firstMonth});
  }

  function handleMonthModeSelect(value: MonthConfig) {
    setFirstMonth(value);
    onChange({mode, firstMonth: value});
  }

  const monthSelector = mode === Modes.Month
    ?
    <SelectControl
      label={__('First month', 'arcw')}
      value={firstMonth}
      help={__('Calendar will display selected month first', 'arcw')}
      options={monthOptions}
      onChange={handleMonthModeSelect}
    />
    : '';

  return <>
    <SelectControl
      label={__('Display', 'arcw')}
      value={mode}
      options={modeOptions}
      onChange={handleModeChange}
    />
    {monthSelector}
  </>;
}
