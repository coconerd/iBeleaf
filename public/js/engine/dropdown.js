function removeVietnameseAccents(str) {
    const accents = {
        'a': ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ', 'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ'],
        'e': ['è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ', 'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ'],
        'i': ['ì', 'í', 'ị', 'ỉ', 'ĩ', 'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ'],
        'o': ['ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ', 'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ'],
        'u': ['ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ', 'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ'],
        'y': ['ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ', 'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ'],
        'd': ['đ', 'Đ']
    };

    for (let nonAccent in accents) {
        const accentedChars = accents[nonAccent];
        for (let char of accentedChars) {
            str = str.replace(new RegExp(char, 'g'), nonAccent);
        }
    }

    return str;
}

function reverseToHref(name) {
    return removeVietnameseAccents(name).toLowerCase().replace(/ /g, "-");
}

list_id = {
    "#indoorPlantsDropdown": {
        "Theo kiểu dáng cây": [                                                                                                               
          "Cây Cao & Lớn Trong Nhà",
          "Cây Cảnh Mini",
          "Cây Treo Trong Nhà",
          "Cây Nhiệt Đới",
          "Cây Kiểng Lá"
        ],
        "Theo vị trí đặt": [
          "Cây Cảnh Để Bàn",
          "Cây Cảnh Văn Phòng",
          "Cây Trong Bếp & Nhà Tắm",
          "Cây Trước Cửa & Hành Lang",
          "Cây Trồng Ban Công"
        ],
        "Theo chức năng": [
          "Cây Lọc Không Khí",
          "Cây Dễ Trồng Trong Nhà",
          "Cây Cần Ít Ánh Sáng",
          "Cây Thủy Sinh",
          "Cây Phong Thủy"
        ],
        "image": "https://mowgarden.com/wp-content/uploads/2021/07/danh-muc-canh-trong-trong-nha.jpg",
        "url": "cay-trong-nha"
    },
    "#outdoorPlantsDropdown": {
        "Theo kiểu dáng": [
          "Cây Tầm Trung",
          "Cây Tầm Thấp",
        //   "Tán Hình Tròn",
        //   "Tán Hình Tháp",
        //   "Tán Phân Tầng"
        ],
        "Theo đặc điểm": [
          "Cây Thường Xanh",
          "Cây Lá Màu",
          "Cây Dạng Bụi",
          "Cây Leo Dàn",
          "Cây Thân Đốt"
        ],
        "Theo chức năng": [
          "Cây Bóng Mát",
          "Cây Có Hoa",
          "Cây Ăn Quả",
          "Cây Hàng Rào",
        //   "Cây Phủ Nền",
        //   "Cỏ Nền"
        ],
        "image": "https://mowgarden.com/wp-content/uploads/2021/07/danh-muc-cay-ngoai-troi.jpg",
        "url": "cay-ngoai-troi"
    },
    "#potDropdown": {
        "Theo kiểu dáng": [
          "Kiểu Chậu Vuông",
        //   "Chậu Chữ Nhật",
          "Kiểu Hình Bầu",
          "Kiểu Hình Bầu Tròn",
          "Kiểu Trụ Đứng",
          "Kiểu Chậu Treo"
        ],
        "Theo vật liệu": [
          "Chậu Nhựa",
          "Chậu Xi Măng",
          "Chậu Đất Nung",
          "Chậu Gốm Sứ",
        //   "Chậu Gỗ"
        ],
        "Theo kích thước": [
          "Chậu Mini Để Bàn",
          "Chậu Cỡ Trung",
          "Chậu Cỡ Lớn",
          "Chậu Cao"
        ],
        "image": window.location.origin + "/images/main/product-introduce/background-pot.png",
        "url": "chau-cay"
    }
}

function addDropBar() {
    var list_html = {};
    for(let id in list_id) {
        var dropBar_html = "";
        for(let title in list_id[id]) {
            if(title == "url")
              continue;
            
            if(title == "image") {
              dropBar_html += `<div class="dropdown-content dropdown-content-img">`;
              dropBar_html += `   <img src="${list_id[id][title]}" alt="">`;
              dropBar_html += `</div>`;

              continue;
            }
              
            dropBar_html += `<div class="dropdown-content">`;
            dropBar_html += `   <h3>${title}</h3>`;
            dropBar_html += `   <ul>`;
            for(let i in list_id[id][title])
                dropBar_html += `   <li><a href="/${reverseToHref(list_id[id][title][i])}">${list_id[id][title][i]}</a></li>`;
            dropBar_html += `   </ul>`;
            dropBar_html += `</div>`;
        }

        

        list_html[id] = dropBar_html;
        $(id).on("click", function() {
            // searchEngine.js
            turnOffSearchBar();
            
            if($(this).hasClass("selected-nav-link")) {
              window.location.replace(window.location.origin + "/" + list_id[id]["url"]);
            }
            else {
              $(".dropdown-bar").html(list_html[id]);
              removeClassSelect();
              $(id).addClass("selected-nav-link");
              showDropDown();
            }
            
        });
    }
}

// turn on navbar
addDropBar();

// turn off navbar
$(".dropdown-bar-close").on("click", ()=>{
  hideDropDown();
});

// init navbar
hideDropDown();
// console.log("done");

function showDropDown() {
  $(".dropdown-bar").show();
  $(".dropdown-bar-close").show();
}

function hideDropDown() {
  removeClassSelect();
  $(".dropdown-bar").hide();
  $(".dropdown-bar-close").hide();
}

function removeClassSelect() {
  $("#indoorPlantsDropdown").removeClass("selected-nav-link");
  $("#outdoorPlantsDropdown").removeClass("selected-nav-link");
  $("#potDropdown").removeClass("selected-nav-link");
}