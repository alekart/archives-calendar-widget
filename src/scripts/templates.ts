import { ArcwConfiguration, Post } from './interfaces';

export default class ArcwHelpers {
  /**
   * Return the number of days for specified month.
   * Takes into account leap years for the month of February.
   */
  static getNumberOfDaysInMonth(month: number, year?: number): number {
    if (month === 2 && !year) {
      throw new Error('The year is mandatory to get number of days for february (2)');
    }
    return new Date(year || 1970, month, 0).getDate();
  }

  /**
   * Create a div or span element with provided content, classes and attributes.
   */
  static createElement(
    tag: 'div' | 'span' | 'select' | 'option',
    content?: string | HTMLElement,
    classes: string[] = [],
    attributes?: { [k: string]: string },
  ): HTMLElement {
    const element: HTMLElement = document.createElement(tag);
    if (classes.length) {
      element.classList.add(...classes);
    }
    if (attributes) {
      Object.keys(attributes).forEach((attributeName) => {
        if (Object.prototype.hasOwnProperty.call(attributes, attributeName)) {
          element.setAttribute(attributeName, attributes[attributeName]);
        }
      });
    }
    if (content && typeof content === 'string') {
      element.innerText = content;
    } else if (content && typeof content === 'object') {
      element.appendChild(content);
    }
    return element;
  }

  static buildDayBox(date: Date | null, posts?: Post[]): HTMLElement {
    const monthClass = 'arcw-day';
    const classes = [monthClass];
    let attributes;
    if (date && posts?.length) {
      classes.push(`${monthClass}--has-posts`);
      attributes = {
        'data-date': date.toISOString(),
      };
    } else {
      classes.push(`${monthClass}--empty`);
    }
    const day = `${date.getDate()}`;
    return ArcwHelpers.createElement('span', day, classes, attributes);
  }

  static buildMonthBox(date: Date | null, config: ArcwConfiguration, posts?: Post[]): HTMLElement {
    const month = date.getMonth();
    const monthClass = 'arcw-month';
    const classes = [monthClass];
    let attributes: Record<string, string> = {
      title: config?.months?.[month]?.full,
    };
    if (date && posts?.length) {
      attributes = {
        ...attributes,
        // TODO: wht is the date for
        'data-date': date.toString(),
      };
    } else {
      classes.push(`${monthClass}--empty`);
    }
    const monthName = config?.months?.[month]?.short;
    const box = ArcwHelpers.createElement('span', null, classes, attributes);
    const monthNameElement = ArcwHelpers.createElement('span', monthName, ['arcw-month__name']);
    box.appendChild(monthNameElement);
    if (date && posts?.length) {
      box.classList.add(`${monthClass}--has-posts`);
      const postCount = ArcwHelpers.createElement('span', null, [`${monthClass}__post-count`]);
      const postNumber = ArcwHelpers.createElement('span', posts.length.toString(), [`${monthClass}__post-count-number`]);
      // TODO "Posts" text should be WP localized in single an plural
      const postText = ArcwHelpers.createElement('span', 'Posts', [`${monthClass}__post-count-text`]);
      postCount.appendChild(postNumber);
      postCount.appendChild(postText);
      box.appendChild(postCount);
    }
    return box;
  }

  static getSelectOption(label: string, value: string): HTMLOptionElement {
    return ArcwHelpers.createElement('option', label, [], { value }) as HTMLOptionElement;
  }
}
