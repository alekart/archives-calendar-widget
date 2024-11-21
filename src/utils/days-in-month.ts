import { isLeapYear } from './is-leap-year';

/**
 * Return the number of days for specified month.
 * Takes into account leap years for the month of February.
 */
export function daysInMonth(month: number, year?: number): number {
  if (month === 1 && !year) {
    throw new Error('Year is mandatory to get number of days in february (2)');
  }
  return [31, (isLeapYear(year as number) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
}
