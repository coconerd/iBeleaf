input_search = $("#input-search");
search_product = $("#search-product");
let max_product = 4;

function AjaxGetProductFilter() {
    fetch(`/search-products`)
    .then(response => response.json()) // Chuyển phản hồi thành JSON
    .then(products => {	
        textInput = input_search.val() + "";
        let search_html = "";
        if(input_search.val() != "") {
            // console.log("data.length 1: ", products.length);

            // console.log("1: ", "Chậu đất nung".toLowerCase());
            // console.log("2: ", textInput.toLowerCase());
            // console.log("2: ", ("Chậu đất nung").toLowerCase().includes(textInput.toLowerCase()));
        

            products = products.filter(product => {
                name_value = product.short_description + "";
                return (name_value.toLowerCase().includes(textInput.toLowerCase())) || 
                    (product.code.toLowerCase() == textInput.toLowerCase()) ||
                    (product.product_id.toLowerCase() == textInput.toLowerCase());
            });

            // console.log("length: ", products.length);

            if (products.length > max_product)
                products = products.slice(0, max_product);

            for(var i = 0; i < products.length; i++) {
                search_html += `<a class="search-info search-spe" href="/product/${products[i].product_id}"> `;
                search_html += `<div class="search-info-img"> <img src="${products[i].image_url}" alt=""> </div> `;
                search_html += `<div class="search-info-content"> `;
                search_html += `    <span  class="search-content-title">${products[i].short_description}</span> `;

                if(products[i].discount_percentage > 0)
                    search_html += `<span  class="search-content-price">
                                        ${products[i].discount_price}₫
                                        <span class="search-discount-price">
                                            ${products[i].price}₫
                                        </span>    
                                    </span> `;
                else
                    search_html += `<span  class="search-content-price">${products[i].price}₫</span> `;
                
                search_html += `</div> </a>`;    
            }

            if(search_html == "")
                search_html = `<div class="empty-search-info search-spe"> <span> ${"Không tìm thấy sản phẩm"} </span> </div> `;
        }
        else {
            search_html = `<div class="empty-search-info search-spe"> <span> ${"Nhập tên cần tìm kiếm"} </span> </div> `;
        }

        

        search_product.html(search_html);
        // console.log("search_html: ", search_html);

        activeSearchPage(textInput);
    })
    .catch(error => {
        console.error('error with searching:', error);
    });
}

input_search.on("input", ()=>{
    AjaxGetProductFilter();
});

AjaxGetProductFilter();

// turn on or off search-bar
// off
function turnOffSearchBar() {
    $("#search-list-products").css("display","none");
    $("#out-search").css("display","none");
}

$("#out-search").on("click", ()=>{
    turnOffSearchBar();
});

// on
$("#input-search").on("click", ()=>{
    $("#search-list-products").css("display","flex");
    $("#out-search").css("display","flex");
});

// active search page
function activeSearchPage(textInput) {
    var linkToSearchPage = "/search?name=" + textInput;
    // console.log(linkToSearchPage);
    $("#more-search").attr('href', linkToSearchPage);
    $("#but-input-search").attr('href', linkToSearchPage);
}
activeSearchPage("");

// disable search
$("#search-list-products").css("display","none");
$("#out-search").css("display","none");

