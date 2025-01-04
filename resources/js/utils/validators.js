export function isValidPalestinianID(id) {
    id = "" + id;
    if (!id) return false;
    return /\d{9}/.test(id) && Array.from(id, Number).reduce((counter, digit, i) => {
      const step = digit * ((i % 2) + 1);
      return counter + (step > 9 ? step - 9 : step);
    }) % 10 === 0;
  }
  