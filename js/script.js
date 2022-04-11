const nextBtn = document.querySelector(".next-btn");
const prevBtn = document.querySelector(".prev-btn");
const slider = document.querySelector(".image-slider");
let count = 1;
let minSliderNum = 1;
let maxSliderNum = 12;

moveSlider(0);

nextBtn.addEventListener("click", () => {
    moveSlider(1);
});
prevBtn.addEventListener("click", () => {
    moveSlider(-1);
});

function moveSlider(num) {
    if(minSliderNum <= count + num && count + num <= maxSliderNum) {
        count += num;
        let nowSlide = slider.querySelector(".selected");
        let nextSlide = slider.querySelector("#slide-" + count);

        nowSlide.classList.remove("selected");
        nextSlide.classList.add("selected");

        if(minSliderNum == count) {
            prevBtn.style.visibility = "hidden";
        } else {
            prevBtn.style.visibility = "visible";
        }

        if(maxSliderNum == count) {
            nextBtn.style.visibility = "hidden";
        } else {
            nextBtn.style.visibility = "visible";
        }
    }
}