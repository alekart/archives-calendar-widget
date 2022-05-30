import Helpers from './helpers';
import { ArcwConfiguration, NavigationItem } from './interfaces';

type Direction = 'next' | 'prev';

export default class Navigation {
  totalPages: number;
  currentPage: number;
  direction: Direction;
  nav: HTMLElement;
  prevButton: HTMLButtonElement;
  nextButton: HTMLButtonElement;
  navLink: HTMLAnchorElement;
  selectMenu: HTMLSelectElement;
  activeIndex: number;
  private selectors = {
    prevButton: '[data-arcw-prev]',
    nextButton: '[data-arcw-next]',
    navLink: '[data-arcw-link]',
    selectMenu: '[data-arcw-select]',
  };

  constructor(
    navElement: HTMLElement,
    private items: NavigationItem[],
    private configuration: ArcwConfiguration,
  ) {
    this.nav = navElement;
    this.prevButton = this.nav.querySelector(this.selectors.prevButton);
    this.nextButton = this.nav.querySelector(this.selectors.nextButton);
    this.navLink = this.nav.querySelector(this.selectors.navLink);
    this.selectMenu = this.nav.querySelector(this.selectors.selectMenu);

    this.items.forEach((item) => {
      const label = this.configuration.mode === 'year'
        ? item.year
        : this.getMonthSelectionLabel(item.month, item.year);
      const selecttOption = Helpers.getSelectOption(label, `${item.index}`);
      this.selectMenu.appendChild(selecttOption);
    });

    this.activeIndex = this.items.findIndex((item) => item.element.classList.contains('arcw-active'));
    console.log(this.activeIndex);

    this.selectMenu.addEventListener('change', (event: any) => {
      const index = parseInt(event.target.value, 10);
      this.goto(index);
    });
  }

  goto(index: number) {
    const activeElement = this.activeIndex >= 0 ? this.items[this.activeIndex].element : null;
    const goingToElement = this.items[index]?.element;
    console.log(activeElement);
    if (activeElement) {
      activeElement.classList.toggle('arcw-active');
    }
    goingToElement?.classList.toggle('arcw-active');
    this.activeIndex = index;
  }

  prev() {
    if (this.totalPages > 1 && this.currentPage < this.totalPages) {
      this.currentPage += 1;
    }
  }

  next() {
    if (this.currentPage > 1) {
      this.currentPage -= 1;
    }
  }

  private addListeners() {
    this.selectMenu.addEventListener('change', (event: any) => {
      console.log(event.target.value);
    });
  }

  // eslint-disable-next-line class-methods-use-this
  private getIndex(selectedValue: string): number {
    return parseInt(selectedValue.split('-')[0], 10);
  }

  private getDirection(pageIndex: number): Direction {
    if (pageIndex > this.currentPage) {
      return 'next';
    }
    return 'prev';
  }

  /**
   * Get the translated month name
   * @param month from 1 to 12
   * @param short when true will return short name
   */
  private getMonthName(month: number | string, short = false): string {
    const monthNum = typeof month === 'string'
      ? parseInt(month, 10) - 1
      : month - 1;
    const monthI18n = this.configuration.months[monthNum];
    return short ? monthI18n.short : monthI18n.full;
  }

  /**
   * Return label for the navigation select menu with the month name and year
   * if archive year is different from current.
   */
  private getMonthSelectionLabel(month: string, year: string): string {
    const currentYear = new Date().getFullYear();
    const yearLabel = year === currentYear.toString() ? '' : ` | ${year}`;
    return this.getMonthName(month) + yearLabel;
  }
}
