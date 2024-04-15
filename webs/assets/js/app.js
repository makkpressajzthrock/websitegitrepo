//Menu JS
const links = document.querySelectorAll(".smooth__scroll");

            for (const link of links) {
            link.addEventListener("click", clickHandler);
            }

            function clickHandler(e) {
            e.preventDefault();
            const href = this.getAttribute("href");
            const offsetTop = document.querySelector(href).offsetTop;

            scroll({
                top: offsetTop,
                behavior: "smooth"
            });
}


// Video 

    const videoElement = document.getElementById('video');
    const playPauseButton = document.getElementById('videoControls');

    if(videoElement) {
        playPauseButton.addEventListener('click', () => {
            playPauseButton.classList.toggle('playing');
            if (playPauseButton.classList.contains('playing')) {
                videoElement.play();
            }
            else {
                videoElement.pause();
            }
        });
    
        videoElement.addEventListener('ended', () => {
            playPauseButton.classList.remove('playing');
        });
    
    
        const vidWrap = document.getElementsByClassName('video-container');
    
        vidWrap[0].addEventListener('mouseover', ()=> {
            var btn = vidWrap[0].getElementsByTagName('button')
            btn[0].style.opacity = "1"
    
            setTimeout(function(){
                btn[0].style.opacity = "0"
            }, 1000)
        })
    
        vidWrap[0].addEventListener('mousemove', ()=> {
            var btn = vidWrap[0].getElementsByTagName('button')
            btn[0].style.opacity = "1"
    
            setTimeout(function(){
                btn[0].style.opacity = "0"
            }, 1000)
        })
    }



    const videoElement2 = document.getElementById('video2');
    const playPauseButton2 = document.getElementById('videoControls2');

    if(videoElement2) {
        playPauseButton2.addEventListener('click', () => {
            playPauseButton2.classList.toggle('playing');
            if (playPauseButton2.classList.contains('playing')) {
                videoElement2.play();
            }
            else {
                videoElement2.pause();
            }
        });
    
        videoElement2.addEventListener('ended', () => {
            playPauseButton2.classList.remove('playing');
        });
    
    
        const vidWrap2 = document.getElementsByClassName('video-container2');
    
        vidWrap2[0].addEventListener('mouseover', ()=> {
            var btn = vidWrap2[0].getElementsByTagName('button')
            btn[0].style.opacity = "1"
    
            setTimeout(function(){
                btn[0].style.opacity = "0"
            }, 1000)
        })
    
        vidWrap2[0].addEventListener('mousemove', ()=> {
            var btn = vidWrap2[0].getElementsByTagName('button')
            btn[0].style.opacity = "1"
    
            setTimeout(function(){
                btn[0].style.opacity = "0"
            }, 1000)
        })
    }



//Sliders


//Form Redirect 

const formCwv = document.getElementsByClassName('form__cwv');
for (let i = 0; i < formCwv.length; i++) {
    if (typeof formCwv[i] === 'undefined') {
        
    }else{
        formCwv[i].addEventListener('submit', (e) => {
            e.preventDefault();
            window.location.href="https://websitespeedy.com/signup.php"; 
        })   
    }
    
}




//FAQ JS
