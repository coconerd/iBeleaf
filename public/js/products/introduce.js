let list_productHref = [
    {
        img: '/images/main/cay-trong-nha.jpg',
        title: "Cây trong nhà",
        des: "Cây trong nhà giúp không gian sống của bạn trở nên tươi mới, thư thái và đầy cảm hứng. Với sản phẩm từ Coconerd, bạn sẽ tận hưởng sự thoải mái trong một không gian xanh hoàn hảo.",
        href: "cay-trong-nha"
    },
    {
        img: '/images/main/cay-ngoai-troi.jpg',
        title: "Cây ngoài trời",
        des: "Cây ngoài trời làm đẹp không gian và mang lại không khí trong lành. Sản phẩm chất lượng từ Coconerd sẽ biến khu vườn của bạn thành nơi thư giãn tuyệt vời.",
        href: "cay-ngoai-troi"
    },
    {
        img: '/images/main/chau-cay.jpg',
        title: "Chậu cây",
        des: "Chậu cây là điểm nhấn tinh tế, nâng niu từng mầm xanh. Với thiết kế đa dạng từ Coconerd, không gian sống của bạn sẽ thêm phần hài hòa và cuốn hút.",
        href: "chau-cay"
    },
]

function add_href_product() {
    var htmHref = "";

    list_productHref.forEach(product => {
        htmHref += `
        <div class="content">
            <div class="content-img">
                <img src="${window.location.origin + product.img}" alt="${product.title}">
            </div>
            <span class="content-title">${product.title}</span>
            <span class="content-des"> ${product.des}</span>
            <a href="${product.href}" class="content-href">READ MORE</a>
        </div>`
    });

    $(".nav-container").append(htmHref);
}

let list_introduce = [
    {
        img: '/images/main/introduce/intro1.svg',
        title: "Chuyên nghiệp - Tận tâm",
        des: "Đội ngũ Tư vấn viên & Chăm sóc Khách hàng kinh nghiệm, chuyên nghiệp, tận tâm. Chúng tôi cam kết bảo hành dịch vụ khi Khách hàng chưa hài lòng."
    },
    {
        img: '/images/main/introduce/intro2.svg',
        title: "Ứng dụng tiện lợi",
        des: "Ứng dụng cung cấp đầy đủ thông tin về dịch vụ, tiện lợi trong việc chủ động lựa chọn và đánh giá."
    },
    {
        img: '/images/main/introduce/intro3.svg',
        title: "Sản phẩm đạt tiêu chuẩn",
        des: "Các sản phẩm cây trồng tiêu chuẩn cao, đáng tin cậy, có đầy đủ loại. Chúng tôi chịu trách nhiệm tuyển chọn, đào tạo và quản lý."
    },
]

function add_nav_product() {
    var htmlIntroduce = "";

    list_introduce.forEach(product => {
        htmlIntroduce += `
        <div class="intro-content">
			<div class="intro-img">
				<img src="${window.location.origin + product.img}" alt=" ">
			</div>  
			<div class="intro-title">
				${product.title}
			</div>
			<div class="intro-des">
                ${product.des}
			</div>
		</div>`
    });

    $(".nav-introduce").append(htmlIntroduce);
}

$(document).ready(function(){
    // alert("introduce is appended successfully");
    add_href_product();
    add_nav_product();
});

		// let number = 0;
		// let list_caytrongnha = [];
		// let list_cayngoaitroi = [];
		// let list_chaucay = [];

		// // a = <?php echo json_encode(ProductData::getCayTrongNha())?>;
		
		
		// function getCayTrongNha() {
		// 	// alert("ấdasd");
		// };
		

		// function postCayTrongNha() {
			
		// };

		// function performAction() {

		// 	// chuyển js vào php để lấy lại mảng
		// 	fetch(`/get-products?number=${number}`)
		// 		.then(response => response.json()) // Chuyển phản hồi thành JSON
		// 		.then(data => {
		// 			alert(data);
		// 		})
		// 		.catch(error => {
		// 			console.error('Lỗi:', error);
		// 	});

		// 	// lấy mảng từ php
		// 	// a = <?php echo json_encode(ProductData::get(1))?>;
			
		// 	// number += 10;


		// 	$("#block").html(text);
		// };	