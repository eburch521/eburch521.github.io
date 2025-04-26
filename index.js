const projectBtn = document.getElementById("projects");
const projectLinks = document.querySelector(".project-links");
const musicBtn = document.getElementById("music");
const musicLinks = document.querySelector(".music-links");
const newsBtn = document.getElementById("news");
const newsLinks = document.querySelector(".news-links");
const otherDivs = document.querySelectorAll("div");
const containers = document.querySelectorAll(".container");
console.log(otherDivs);
let x = 0;
let y = 0;

projectBtn.addEventListener('click', function(){
    projectLinks.classList.toggle('show-links');
    projectBtn.classList.toggle('flip-div');
});
musicBtn.addEventListener('click', function(){
    musicLinks.classList.toggle('show-links');
    musicBtn.classList.toggle('flip-div');
});
newsBtn.addEventListener('click', function(){
    newsLinks.classList.toggle('show-links');
    newsBtn.classList.toggle('flip-div');
    
});
otherDivs.forEach(div => div.addEventListener('click', function(){
    x +=  20 ;
    y +=  20 ;
    theta = 2 * Math.PI * Math.sqrt((x/200) ** 2 + (y/200) ** 2);;
    console.log(x,y, theta);
    //div.style.transform = `translate(${x}px, ${y}px) rotate(${theta}rad)`;
    divColor = "rgba(" +
    Math.round(Math.random() * 250) +
    "," +
    Math.round(Math.random() * 250) +
    "," +
    Math.round(Math.random() * 250) +
    "," +
    (10 + Math.ceil(Math.random() * 10) / 10) +
    ")"
    console.log(divColor);
    div.style.backgroundColor = `${divColor}`;

    div.classList.toggle('flip-div');
}));

containers.forEach(container => container.addEventListener('click', function(){
    
    x += 10 ;
    y += 20 ;
    theta = 2 * Math.PI * Math.sqrt((x/200) ** 2 + (y/200) ** 2);
    console.log(x,y, theta);
   // container.style.transform = `translate(${x}px, ${y}px) rotate(${theta}rad)`;
    divColor = "rgba(" +
    Math.round(Math.random() * 250) +
    "," +
    Math.round(Math.random() * 250) +
    "," +
    Math.round(Math.random() * 250) +
    "," +
    (10 + Math.ceil(Math.random() * 10) / 10) +
    ")"
    console.log(divColor);
    container.style.backgroundColor = `${divColor}`;

    div.classList.toggle('flip-div');

} ));



