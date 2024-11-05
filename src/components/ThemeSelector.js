import {useState} from 'react';
import {SelectControl} from '@wordpress/components';

function slugToText(slug) {
  return slug
    .replace(/([-_])/g, ' ')
    .replace(/(\d)/g, ' $1');
}

const themes = [
  'theme1',
  'theme2',
].map((theme) => ({
  label: slugToText(theme),
  value: theme,
}));

export function ThemeSelector({
  currentTheme = themes[0],
  onChange = (theme) => {
  },
}) {
  const [theme, setTheme] = useState(currentTheme);

  function handleThemeChange(value) {
    setTheme(value);
    onChange(value);
  }

  return <>
    <SelectControl
      value={theme}
      options={themes}
      selected={theme}
      onChange={handleThemeChange}
    />
  </>;
}
