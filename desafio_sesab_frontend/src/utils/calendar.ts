let today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();
export const months = [
  "Janeiro",
  "Fevereiro",
  "março",
  "Abril",
  "Maio",
  "Junho",
  "Julho",
  "Agosto",
  "Setembro",
  "Outubro",
  "Novembro",
  "Dezembro",
];

export const years = () => {
  let result = [];
  for (let i = 1900; i < 2100; i++) result.push(i);
  return result;
};

export function prepareCalendar(month: number, year: number) {
  const firstWeekDay = new Date(year, month).getDay();
  const daysInMonth = 32 - new Date(year, month, 32).getDate();
  let date = 1;
  let calendar = [];
  for (let i = 0; i < 6; i++) {
    let row = [];
    for (let j = 0; j < 7; j++) {
      if (i === 0 && j < firstWeekDay) {
        row.push({ value: "", isToday: false, isSelected: false });
      } else if (date > daysInMonth) break;
      else {
        const day = { value: date, isToday: false, isSelected: false };
        if (
          date === today.getDate() &&
          year === today.getFullYear() &&
          month === today.getMonth()
        ) {
          day.isToday = true;
        }
        row.push(day);
        date++;
      }
    }
    calendar.push(row);
  }
  return calendar;
}
