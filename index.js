const projectsBtn = document.getElementById('projects-btn');
const musicBtn = document.getElementById('music-btn');
const newsBtn = document.getElementById('news-btn');
const projectsLinks = document.querySelectorAll('.projects-links');
const musicLinks = document.querySelector('.music-links');
const newsLinks = document.querySelector('.news-links');



projectsBtn.addEventListener('click', () => {
    projectsLinks.forEach(link => {
        link.classList.toggle('hidden');
    });
});
musicBtn.addEventListener('click', () => {
    musicLinks.classList.toggle('hidden');
});
newsBtn.addEventListener('click', () => {
    newsLinks.classList.toggle('hidden');
});