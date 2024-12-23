list_categories = $(".menu-category .row-menu");

list_categories.each(function() { 
    if($(this).find(".mega-links li").length > 0) {
        i_tag = $(this).find(".header-row .toggle i");
        if(i_tag) {
            i_tag.addClass("arrowDown");
            // console.log($(this).find(".header-row a").text());
        }

        dis_button = $(this).find(".header-row button");
        dis_button.on("click", function() {
            arrow = $(this).find("i");
            
            arrow.toggleClass("arrowDown");
            arrow.toggleClass("arrowUp");

            mega_link = $(this).closest('.row-menu').find(".mega-links");
            if($(this).find(".arrowDown").length > 0)
                mega_link.css("display", "flex");
            else
                mega_link.css("display", "none");   
        });
    }
    else {
        dis_button = $(this).find(".header-row button");
        if(dis_button) {
            dis_button.css("display","none");
        }
    }
});

function togglearrow(event) {
    event.preventDefault();
    console.log("success");
}

