function validateCaravanForm(form) {
  const mileage = form.mileage.value.trim();
  const year = form.year.value.trim();
  const doors = form.num_doors.value.trim();

  // Check if mileage is a number
  if (isNaN(mileage) || mileage <= 0) {
    alert("Please enter a valid mileage.");
    form.mileage.focus();
    return false;
  }

  // Check if year is 4 digits and realistic
  if (
    !/^\d{4}$/.test(year) ||
    parseInt(year) < 1900 ||
    parseInt(year) > new Date().getFullYear()
  ) {
    alert("Please enter a valid year.");
    form.year.focus();
    return false;
  }

  // Check if doors is a reasonable number
  if (isNaN(doors) || doors <= 0 || doors > 10) {
    alert("Please enter a valid number of doors.");
    form.num_doors.focus();
    return false;
  }

  return true; // form is valid
}
