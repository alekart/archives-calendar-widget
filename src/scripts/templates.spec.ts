import ArcwHelpers from './templates';

describe('ARCW Helpers', () => {
  describe('getNumberOfDaysForMonth', () => {
    it('should return correct number of days for non February month without year', () => {
      const months = [31, null, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
      for (let i = 1; i <= 12; i += 1) {
        if (i !== 2) {
          expect(ArcwHelpers.getNumberOfDaysInMonth(i)).toBe(months[i - 1]);
        }
      }
    });

    it('should return 29 days for February in leap year', () => {
      expect(ArcwHelpers.getNumberOfDaysInMonth(2, 2020)).toBe(29);
      expect(ArcwHelpers.getNumberOfDaysInMonth(2, 2016)).toBe(29);
    });

    it('should return 28 days for February in non-leap year', () => {
      expect(ArcwHelpers.getNumberOfDaysInMonth(2, 2019)).toBe(28);
      expect(ArcwHelpers.getNumberOfDaysInMonth(2, 2021)).toBe(28);
    });

    it('should throw if year is not provided for February', () => {
      expect(() => ArcwHelpers.getNumberOfDaysInMonth(2)).toThrow();
    });
  });
});
