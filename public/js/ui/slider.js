var list_range = $(".slider-price input");
let input_min = $(".field .input-min");
let input_max = $(".field .input-max");
let slider = $(".slider-price .progress-slider");
let minGap = 350000

function changeValueSlider(minValue, maxValue) {
    var range = list_range[1].max - list_range[1].min;
    var min = list_range[1].min;
    slider.css("left", (minValue - min) * 100 / range + "%");
    slider.css("right", 100 - (maxValue - min) * 100 / range + "%");
}

changeValueSlider(list_range[0].value, list_range[1].value);

list_range.each(function() {
    $(this).on("input", e =>{
        
        
        var minValue = parseInt(list_range[0].value),
            maxValue = parseInt(list_range[1].value);

        if(maxValue - minValue < minGap) {
            if(e.target.className === "min-range")
                list_range[0].value = maxValue - minGap;
            else    
                list_range[1].value = minValue + minGap;

            minValue = list_range[0].value;
            maxValue = list_range[1].value;
        }
        
        changeValueSlider(minValue, maxValue);
        
        // console.log("minValue: ", (minValue - min) * 100 / range, " maxValue: ",(maxValue - min) * 100 / range);
        // console.log(maxValue - minValue)

        input_min.val(list_range[0].value);
        input_max.val(list_range[1].value);
    })
});

input_min.on('input', function() {
    var maxValue = parseInt(list_range[1].value) - minGap;

    $(this).val(Math.min($(this).val(), maxValue));
    $(this).val(Math.max($(this).val(), list_range[0].min));

    list_range[0].value = $(this).val();
    changeValueSlider(list_range[0].value, list_range[1].value);
});

input_max.on('input', function() {
    var minValue = parseInt(list_range[0].value) + minGap;

    $(this).val(Math.min($(this).val(), list_range[1].max));
    $(this).val(Math.max($(this).val(), minValue));

    list_range[1].value = $(this).val();
    changeValueSlider(list_range[0].value, list_range[1].value);
});

input_min.on('invalid', event => {
    event.preventDefault(); 
});

input_max.on('invalid', event => {
    event.preventDefault(); 
});