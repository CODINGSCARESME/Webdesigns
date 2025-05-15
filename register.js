function validateForm(form) {
  const username = form.username.value.trim();
  const password = form.password.value.trim();
  const email = form.email.value.trim();
  const telephone = form.telephone.value.trim();

  if (username.length < 4) {
    alert("Username must be at least 4 characters.");
    form.username.focus();
    return false;
  }

  if (password.length < 6) {
    alert("Password must be at least 6 characters.");
    form.password.focus();
    return false;
  }

  const phonePattern = /^[0-9]{10,15}$/;
  if (!phonePattern.test(telephone)) {
    alert("Please enter a valid telephone number (10–15 digits).");
    form.telephone.focus();
    return false;
  }

  if (!email.includes("@")) {
    alert("Please enter a valid email address.");
    form.email.focus();
    return false;
  }

  return true;
}
