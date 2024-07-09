const clap = new Audio('./sounds/clap.wav');
const hihat = new Audio('./sounds/hihat.wav');
const kick = new Audio('./sounds/kick.wav');
const openhat = new Audio('./sounds/openhat.wav');
const boom = new Audio('./sounds/boom.wav');
const ride = new Audio('./sounds/ride.wav');
const snare = new Audio('./sounds/snare.wav');
const tom = new Audio('./sounds/tom.wav');
const tink = new Audio('./sounds/tink.wav');

const bpmInput = document.getElementById('bpm');

const flamInput = document.getElementById('flam-span');

const stepLengthInput = document.getElementById('step-length');

const playBtn = document.getElementById('play-btn');

let keys = Array.from(document.querySelectorAll('.key')); 

let beats = Array.from(document.querySelectorAll('.beat'));

let beatLights = Array.from(document.querySelectorAll('.beatlight'));

let sequencer = document.getElementById('sequencer');
console.log(sequencer);



let currentStep = 0;
let bpm = 120;
let stepLength = 16;
let flamLength = 40;
let beatPlaying = false;
let seqIndex = 0;
let interval;

let clapSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let hihatSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let kickSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let openhatSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let boomSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let rideSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let snareSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let tomSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let tinkSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

let clapSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let hihatSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let kickSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let openhatSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let boomSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let rideSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let snareSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let tomSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
let tinkSeqFlam =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

let flamSeq = [clapSeqFlam, hihatSeqFlam,kickSeqFlam, openhatSeqFlam, boomSeqFlam, rideSeqFlam, snareSeqFlam, tomSeqFlam, tinkSeqFlam];

let drumSeq = [clapSeq, hihatSeq, kickSeq, openhatSeq, boomSeq, rideSeq, snareSeq, tomSeq, tinkSeq];
const drumKit = [clap, hihat, kick, openhat, boom, ride, snare, tom, tink];
let selectedDrum = drumKit[0];

let beatCell = document.createElement('div');
let beat = document.createElement('div');
let beatLight = document.createElement('div');
beatCell.classList.add('beat-cell');
beat.classList.add('beat');
beatLight.classList.add('beatlight');

console.log(beatCell);




    
keys.forEach(key => key.addEventListener('click', selectDrum));
keys.forEach(key => key.addEventListener('transitionend', removeTransition));
window.addEventListener('keydown', typeSound);
beats.forEach(beat => beat.addEventListener('click', beatSwitch));
beatLights.forEach(beatLight => beatLight.addEventListener('click', flam));
window.addEventListener('keypress', event => {
    if(event.code === 'Space'){
    
    if(beatPlaying) {stopBeat()}
    else{playBeat()}
    }
    });

function flam() {
    this.classList.toggle('flam');
    for(i = 0; i<stepLength; i++){
        if(beatLights[i].classList.contains('flam')){
            flamSeq[seqIndex][i] = 1;
        } 
        else{
            flamSeq[seqIndex][i] = 0;
        }
       
    }  
    console.log(flamSeq);       
}

function selectDrum() {
    
    selectedDrum = this.id;
    seqIndex = keys.findIndex((key)=> key.id == `${this.id}`);
    for(j = 0; j<stepLength; j++){
    
            if(flamSeq[seqIndex][j] != 0){
                beatLights[j].classList.add('flam');
            }
            else{beatLights[j].classList.remove('flam')};
        }
    
    
    let selectedDrumAudio = drumKit[seqIndex];

    keys.forEach(key => (key.style.backgroundColor = 'black'));
    this.style.backgroundColor = 'orange';
    console.log(selectedDrum);
    console.log(seqIndex);

    this.classList.add('playing');
    selectedDrumAudio.currentTime = 0;
    selectedDrumAudio.play();

    for(i = 0; i<stepLength; i++){
        if(drumSeq[seqIndex][i] != 0){
            beats[i].classList.add('note-on');
        }
        else{beats[i].classList.remove('note-on')};
    }
}

function removeTransition(event) {
    if (event.propertyName !== 'transform') return;
    event.target.classList.remove('playing');
}
function typeSound(event) {

    let audio = document.querySelector(`audio[data-key="${event.keyCode}"]`);
    let key = document.querySelector(`div[data-key="${event.keyCode}"]`);

    if (!audio) return;

    key.classList.add('playing');
    audio.currentTime = 0;
    audio.play();
}
function beatSwitch() {

    console.log(this.id);
    this.classList.toggle('note-on');
    
    for(i = 0; i<stepLength; i++){
        if(beats[i].classList.contains('note-on')){
            drumSeq[seqIndex][i] = 1;
        } 
        else{
            drumSeq[seqIndex][i] = 0;
        }

    }    
    console.log(drumSeq[seqIndex]);


    
}
window.addEventListener('keydown', function(event) {
        if(event.key == "Enter"){
            if(bpmInput.value){
                bpm = bpmInput.value;
            }
            else{bpm = 120};
            if(stepLengthInput.value){
                stepLength = stepLengthInput.value;
            }
            else{stepLength = 16};
            console.log(stepLength);
            console.log(bpm)
            changeStepLength(stepLength);
         }
        if(!interval){
                playBeat();
            }
        }
    
    );

function changeStepLength(stepLength) {
    console.log(stepLength);
    sequencer.innerHTML = '';
    for(let k = 1; k <= stepLength; k++){
        beatCell = document.createElement('div');
        beat = document.createElement('div');
        beatLight = document.createElement('div');
        beatCell.classList.add('beat-cell');
        beat.classList.add('beat');
        beatLight.classList.add('beatlight');
        beat.textContent = k;
        beatCell.appendChild(beat);
        beatCell.appendChild(beatLight);
        sequencer.appendChild(beatCell);
        }
        
    
    beats = Array.from(document.querySelectorAll('.beat'));

    beatLights = Array.from(document.querySelectorAll('.beatlight'));


    
    

    for(let j = 0; j < drumSeq.length; j++) {   
        let seq = [];
    for(let i = 0; i < stepLength; i++){
        if(drumSeq[j][i] == null){
            seq.push(0);

        }
        else{seq.push(drumSeq[j][i])};

    }    
        drumSeq[j] = seq;
        console.log(drumSeq[j]);
    } 
    for(let j = 0; j < flamSeq.length; j++) {   
        let fseq = [];
    for(let i = 0; i < stepLength; i++){
        if(flamSeq[j][i] == null){
            fseq.push(0);

        }
        else{fseq.push(flamSeq[j][i])};

    }    
    flamSeq[j]=fseq;
}  
    for(i = 0; i<stepLength; i++){
        if(drumSeq[seqIndex][i] != 0){
            beats[i].classList.add('note-on');
        }
        else{beats[i].classList.remove('note-on')};
    }
    for(j = 0; j<stepLength; j++){
        if(flamSeq[seqIndex][j] != 0){
            beatLights[j].classList.add('flam');
        }
        else{beatLights[j].classList.remove('flam')};
    }
    beats.forEach(beat => beat.addEventListener('click', beatSwitch));    
    beatLights.forEach(beatLight => beatLight.addEventListener('click', flam));
    playBeat();
};


function playBeat() {
   flamLength = flamInput.value;
    
    if (!beatPlaying) {
        beatPlaying = true;
        playBtn.disabled = true;
    }
    if (interval) {
        clearInterval(interval);
    }

    interval = setInterval(() => {
            beatLights.forEach(beatLight => {
                if(beatLight.classList.contains('flam')){beatLight.style.backgroundColor = 'orange'}
                else{beatLight.style.backgroundColor = 'red'}
    });
            beatLights[currentStep].style.backgroundColor = 'yellow';  

            drumSeq.forEach(seq => {
                if(seq[currentStep]!=0){
                drumKit[drumSeq.indexOf(seq)].currentTime = 0;
                drumKit[drumSeq.indexOf(seq)].play();
                if(flamSeq[drumSeq.indexOf(seq)][currentStep] != 0){
                setTimeout(()=>{
                drumKit[drumSeq.indexOf(seq)].currentTime = 0;
                drumKit[drumSeq.indexOf(seq)].play();
            },flamLength)};
            }
        });
            currentStep = (currentStep+1) % stepLength;
        }, (60/bpm)*250); // Adjust tempo here
    
}


function stopBeat() {
    console.log(beatPlaying);
    currentStep = 0;
    clearInterval(interval);
    if (beatPlaying) {
        beatPlaying = false;
        playBtn.disabled = false;
        console.log('Stopped.');
    }


}

function clearBeat() {
    
    beats.forEach(beat => {beat.classList.remove('note-on')})
    
    
    clapSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    hihatSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    kickSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    openhatSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    boomSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    snareSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    tomSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    tinkSeq =[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

    drumSeq = [clapSeq, hihatSeq, kickSeq, openhatSeq, boomSeq, snareSeq, tomSeq, tinkSeq];



}

function beatStep() {
    beatLights.forEach(beatLight => beatLight.style.backgroundColor = 'red');
    beatLights[currentStep].style.backgroundColor = 'yellow';  

    drumSeq.forEach(seq => {
                if(seq[currentStep]!=0){
                drumKit[drumSeq.indexOf(seq)].currentTime = 0;
                drumKit[drumSeq.indexOf(seq)].play();
            }
        });
    }

