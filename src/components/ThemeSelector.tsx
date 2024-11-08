import { useState } from 'react';
import { SelectControl } from '@wordpress/components';
import { noop } from '../utils/noop';
import { slugToText } from '../utils/slug-to-label';

const themes = [
  'theme1',
  'theme2',
];

const themesOptions: { label: string, value: string }[] = themes.map((theme) => ({
  label: slugToText(theme),
  value: theme,
}));

export function ThemeSelector({currentTheme = themes[0], onChange = noop}: {
  currentTheme: string;
  onChange: (t: string) => void
}) {
  const [theme, setTheme] = useState(currentTheme);

  function handleThemeChange(value: string) {
    setTheme(value);
    onChange(value);
  }

  return <>
    <SelectControl
      value={theme}
      options={themesOptions}
      onChange={handleThemeChange}
    />
  </>;
}
