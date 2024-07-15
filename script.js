const wrapper = document.querySelector('.wrapper');
const signUpLink = document.querySelector('.signUpBtn-link');
const signInLink = document.querySelector('.signInBtn-link');

signUpLink.addEventListener('click', () => {
    wrapper.classList.add('active');
});

signInLink.addEventListener('click', () => {
    wrapper.classList.remove('active');
});

document.querySelector('.signupBtn-link').addEventListener('click', () => {
    document.querySelector('.login-container').classList.add('signup-mode');
  });
  const signupBtnLink = document.querySelector('.signupBtn-link');

signupBtnLink.addEventListener('click', () => {
    document.querySelector('.wrapper').classList.toggle('active');
});
