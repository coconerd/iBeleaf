function add_choose_intro(list_choose, img_choose) {
    var htmlIntroduce = `
    <div class="box-choose left"> `;

    list_choose.forEach(info => {
        if(info.type == "left") {

            htmlIntroduce += `
            <div class="box">
                <div class="box-ico">
                    <img src="${window.location.origin + info.img}" alt="">
                </div>
                <div class="box-text">
                    <h1>${info.title}</h1>
                    <span>${info.des}</span>
                </div>
            </div> `;
        }        
    });

    htmlIntroduce += `
    </div>

        <div class="img-choose">
            <img src="${window.location.origin + img_choose}" alt="">
        </div>

    <div class="box-choose right"> `;

    list_choose.forEach(info => {
        if(info.type == "right") {

            htmlIntroduce += `
            <div class="box">
                <div class="box-ico">
                    <img src="${window.location.origin + info.img}" alt="">
                </div>
                <div class="box-text">
                    <h1>${info.title}</h1>
                    <span>${info.des}</span>
                </div>
            </div> `;
        }        
    });

    htmlIntroduce += `
    </div> `;

    $(".choose-container").html(htmlIntroduce);
}


$(document).ready(function(){
    var title = $(".choose-container").attr("name");

    var list_choose = [
        {
            type: "left",
            img: "/images/main/choose-info/icon_why_1.webp",
            title: "Sứ mệnh “Vì không gian xanh”",
            des: `${title} lấy khách hàng làm trung tâm, xem sự hài lòng của khách hàng là kim chỉ nam trong mọi hoạt động. Chúng tôi không ngừng nỗ lực mang đến những sản phẩm cây cảnh và chậu trồng chất lượng, góp phần tạo nên không gian sống xanh, sạch và tràn đầy sức sống cho mọi gia đình.`
        }, {
            type: "left",
            img: "/images/main/choose-info/icon_why_2.webp",
            title: "Nguồn gốc xuất xứ rõ ràng",
            des: `Đây là tiêu chí quan trọng hàng đầu trong việc hợp tác với các đơn vị cung cấp cây trồng và chậu cây. Các nhà vườn và nhà sản xuất chậu cây của ${title} đều là những đối tác uy tín, đảm bảo cây và sản phẩm có nguồn gốc rõ ràng, tuân thủ các tiêu chuẩn về chất lượng và bảo vệ môi trường.`
        }, {
            type: "left",
            img: "/images/main/choose-info/icon_why_3.webp",
            title: "Quy trình kiểm soát chất lượng chặt chẽ",
            des: `Chúng tôi xây dựng một quy trình kiểm soát chất lượng kỹ lưỡng để đảm bảo cây cảnh luôn khỏe mạnh, xanh tốt và chậu cây đạt tiêu chuẩn cao. Từ việc lựa chọn giống cây, chăm sóc, đến bảo quản và vận chuyển, mọi khâu đều được thực hiện một cách chuyên nghiệp để mang lại sản phẩm hoàn hảo nhất.`
        }, {
            type: "right",
            img: "/images/main/choose-info/icon_why_4.webp",
            title: "Chính sách giá ưu đãi",
            des: `Chính sách giá ưu đãi luôn là một yếu tố quan trọng khi khách hàng lựa chọn sản phẩm. Hiểu được điều đó, ${title} cam kết mang đến những sản phẩm và chậu cây với các mức giá phải chăng, đồng thời thường xuyên triển khai các chương trình ưu đãi hấp dẫn để tri ân khách hàng.`
        }, {
            type: "right",
            img: "/images/main/choose-info/icon_why_5.webp",
            title: "Nguồn hàng phong phú, ổn định",
            des: `Sự đa dạng về các loại cây và chậu cây là một điểm nổi bật của ${title}. Chúng tôi không ngừng mở rộng danh mục sản phẩm, từ cây trang trí trong nhà, cây ngoài trời, đến các chậu cây có thiết kế độc đáo, phù hợp với mọi không gian và phong cách của khách hàng.`
        }, {
            type: "right",
            img: "/images/main/choose-info/icon_why_6.webp",
            title: "Tận tâm, chu đáo, nhiệt tình",
            des: `Không chỉ dừng lại ở sản phẩm, ${title} còn chú trọng đến trải nghiệm dịch vụ của khách hàng. Đội ngũ nhân viên của chúng tôi luôn sẵn sàng tư vấn và hỗ trợ với thái độ tận tâm, chu đáo, nhiệt tình. “Từ thiên nhiên đến không gian sống của bạn” chính là phương châm mà chúng tôi luôn hướng đến.`
        }, 
    ];

    var img_choose = "/images/main/choose-info/choose-image-1.png"; 

    add_choose_intro(list_choose, img_choose);
    // alert($(".choose-container").attr("name"));
});
