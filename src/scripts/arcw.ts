import ArcwCalendar from './arcw-cal';

declare let ajaxurl: string;
declare let arcwdev: boolean;

(() => {
  const calElements: NodeListOf<HTMLElement> = document.querySelectorAll('.archives-calendar');
  const calendars: ArcwCalendar[] = [];
  calElements.forEach((cal) => {
    calendars.push(new ArcwCalendar(cal, ajaxurl, arcwdev));
  });
})();
