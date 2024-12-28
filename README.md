<div align="center">
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/192158954-f88b5814-d510-4564-b285-dff7d6400dad.png" alt="HTML" title="HTML"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/183898674-75a4a1b1-f960-4ea9-abcb-637170a00a75.png" alt="CSS" title="CSS"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/183898054-b3d693d4-dafb-4808-a509-bab54cf5de34.png" alt="Bootstrap" title="Bootstrap"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/117447155-6a868a00-af3d-11eb-9cfe-245df15c9f3f.png" alt="JavaScript" title="JavaScript"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/183570228-6a040b9f-3ddf-47a2-a201-743121dac664.png" alt="php" title="php"/></code>
	<code><img width="30" src="https://github.com/marwin1991/profile-technology-icons/assets/25181517/afcf1c98-544e-41fb-bf44-edba5e62809a" alt="Laravel" title="Laravel"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/183896128-ec99105a-ec1a-4d85-b08b-1aa1620b2046.png" alt="MySQL" title="MySQL"/></code>
</div>

<div align="center"> <h1>Plant Paradise</h1> </div>

Mệt mỏi với cuộc sống xô bồ? Bạn đang tìm kiếm một góc xanh tươi để thư giãn và tận hưởng cuộc sống? **Plant Paradise** chính là thiên đường mà bạn đang tìm kiếm! Với vô vàn giống cây xanh độc đáo, từ những chậu cây nhỏ xinh đến những cây cảnh lớn, chúng tôi cam kết mang đến cho bạn một không gian sống xanh mát và tràn đầy sức sống. Hãy để **Plant Paradise** giúp bạn tạo nên một thiên đường xanh ngay tại ngôi nhà của mình!

## 1. Giới thiệu đồ án môn học 
- **Tên môn học**: Phát triển ứng dụng Web
- **Mã lớp**: IS207.P11
- **Tên đồ án**: Website thương mại điện tử cho cửa hàng bán cây cảnh **Plant Paradise**

## 2. Nhóm thực hiện
**Tên nhóm: Coconerd** 🥥

| Họ và tên          | MSSV     | Vai trò     | Liên hệ                     |
|--------------------|----------|-------------|-----------------------------|
|🌱  Nguyễn Đỗ Đức Minh | 22520872 | Team leader   | nddminh2021@gmail.com          |
|🌱  Phan Thị Thủy Hiền | 22520423 | Team member | thuyhienphanthi2004@gmail.com |
|🌱  Trần Vũ Bão   | 22520124 | Team member | tranvubao2004@gmail.com          |
|🌱  Phan Thành Công       | 22520170 | Team member | phanthanhcong982004@gmail.com          |

## 3. Thiết kế Cơ sở dữ liệu 
[dbdiagram.io](https://dbdiagram.io/d/Plant-Paradise-Database-672671edb1b39dd85843f893)

![image](https://github.com/user-attachments/assets/635edd76-679f-49d3-9084-0468eb105189)

## 4. Quy trình nghiệp vụ

### 4.1 Chính sách bán hàng
- Về việc áp dụng Voucher: Mỗi đơn mua hàng chỉ được áp dụng 1 loại Voucher
- Về chính sách giao hàng:
    - Các sản phẩm cây có kèm chậu: Chỉ nhận giao nội thành TP.HCM
    - Các sản phẩm chậu: Giao hàng toàn quốc  
- Về quy định phí giao hàng:
    - Giao hàng nội thành TP.HCM: Đồng giá 30.000đ
    - Giao hàng ngoại thành TP.HCM: Được tính dựa trên tổng cân nặng của đơn hàng và địa điểm nhận hàng (sử dụng API của GHN - Giao hàng nhanh)
### 4.2 Chính sách trả hàng / hoàn tiền 

## 5. Cài đặt
### 5.1 Yêu cầu hệ thống 
### 5.2 Các bước cài đặt 
### 5.3 Cài đặt Database
- Schema database (MySQL): `plant_paradise_final.sql`
- Tạo mock data (Testing): `php artisan db:seed`

## 6. Giao diện 

<pre>
<strong>Trang chính</strong>
├── <a href="#Trang-đăng-kí--đăng-nhập">Trang đăng ký / đăng nhập</a>
│  
├── <a href="#Trang-chủ">Trang chủ</a>
│   │
│   └── <a href="#Trang-danh-mục-sản-phẩm">Trang danh mục sản phẩm</a>
│   │
│   └── <a href="#Trang-chi-tiết-sản-phẩm">Trang chi tiết sản phẩm</a>
│  
├── <a href="#Trang-giỏ-hàng">Trang giỏ hàng</a>
│   │
│   └── <a href="#Trang-thanh-toán">Trang thanh toán</a>
│   
└── <a href="#Trang-hồ-sơ">Trang hồ sơ</a>
    │
    └── <a href="#Trang-hồ-sơ--đổi-mật-khẩu">Trang thông tin / Đổi mật khẩu</a>
    │    
    └── <a href="#Trang-">Trang .....</a>

<strong>Trang dành cho Admin</strong>
├── <a href="#Trang-đăng-nhập">Đăng ký / đăng nhập</a>
│    
├── <a href="#Trang-Dashboard">Dashboard</a>
│    
└── <a href="#branch-b">Branch B</a>
    └── <a href="#sub-branch-b1">Sub-branch B.1</a>
        └── <a href="#leaf-b11">Leaf B.1.1</a>
</pre>

<hr>

## 🌱 Trang chính
### Trang đăng ký / đăng nhập
> ✨ Đăng ký với Email
> 
![image](https://github.com/user-attachments/assets/5571fb0d-0420-4ea8-a325-b2b90e6b6436)

> ✨ Đăng nhập
> 
- Đăng nhập với Email
- Đăng nhập với Google
- Đăng nhập với Facebook

![image](https://github.com/user-attachments/assets/0776478c-51e1-49bb-ad14-b22a0e380f83)

### Trang chủ

![image](https://github.com/user-attachments/assets/f8d86e84-1f5c-444e-903f-fe853cfdacc4)
    
![image](https://github.com/user-attachments/assets/57e8d5bd-4b61-4674-a5b6-7d1e6559429c)
    
![image](https://github.com/user-attachments/assets/e629a937-1847-4ae7-87f2-02b2d8b32879)
    
![image](https://github.com/user-attachments/assets/b4780b9f-4e82-4ce0-9855-1e6833468e4b)
    
![image](https://github.com/user-attachments/assets/81fa7d78-162a-4d5d-8439-62060cb2e0a1)

> ✨ Tìm kiếm sản phẩm
> 
 ![image](https://github.com/user-attachments/assets/9c989814-03b8-41f2-b279-bb025018e00a)


### Trang danh mục sản phẩm

![image](https://github.com/user-attachments/assets/d878a5b7-a929-45f9-baff-7c47f17351d2)

![image](https://github.com/user-attachments/assets/b5b35c83-4b16-48a9-ade7-0cf5262d4619)

> ✨ Chọn danh mục sản phẩm
> 
 ![image](https://github.com/user-attachments/assets/73d25573-7f7a-4855-ad0f-b6ed39749de7)

> ✨ Chọn khoảng giá mong muốn
> 
 ![image](https://github.com/user-attachments/assets/8919dd8f-7ad2-4e31-905c-0e56aa1ce6e0)
 
> ✨ Filter sản phẩm
> 

### Trang chi tiết sản phẩm

![image](https://github.com/user-attachments/assets/d7204d91-a51d-410c-8465-1eb6d6991cf0)
> ✨ Danh sách các sản phẩm tương tự
>
 ![image](https://github.com/user-attachments/assets/b5bb1442-ed9b-4d6d-bfee-ae82e73693db)

> ✨ Danh sách các sản phẩm đang được khuyến mãi
>
 ![image](https://github.com/user-attachments/assets/3a45ae80-b8fb-44e3-bf07-2aec29f22b3e)

> ✨ Danh sách Feedback của các khách hàng đã mua sản phẩm 
>
 ![image](https://github.com/user-attachments/assets/71c3d46b-f92a-40a8-b530-6e8bce563538)

> ✨ Để lại Feedback cho sản phẩm 
>
 ![image](https://github.com/user-attachments/assets/7b79565a-a50c-4146-8743-a36bf1922b6e)
 
> ✨ Thêm sản phẩm vào giỏ hàng
>
 ![image](https://github.com/user-attachments/assets/9753d53b-382c-492c-90e5-b8f0ee94ab18)

> ✨ Thêm sản phẩm vào danh sách yêu thích
>
 ![image](https://github.com/user-attachments/assets/de4c15b6-be70-4e3d-a318-a625aaf3ead0)

### Trang giỏ hàng 

![image](https://github.com/user-attachments/assets/0b98057c-f810-4ccc-8632-ef42bdfb83a8)

> ✨ Xóa sản phẩm ra khỏi giỏ hàng
>
 ![image](https://github.com/user-attachments/assets/30fd3d6a-26b8-4d00-b552-67c516ed5071)

 ![image](https://github.com/user-attachments/assets/08a72c0c-46b1-481e-9290-6d981fb8ef7c)

 ![image](https://github.com/user-attachments/assets/8949796b-17f7-4ebb-8ca8-268692f6d25e)


> ✨ Áp dụng Voucher cho đơn hàng hiện tại 
>
 ![image](https://github.com/user-attachments/assets/5fa2fab4-6626-4628-8003-a7c90819e0ac)

 ![image](https://github.com/user-attachments/assets/4c2cdbb0-0d72-48e9-8d40-2c824bce2165)

 ![image](https://github.com/user-attachments/assets/cf59dc21-8c6b-43cd-b60e-041cdef3438b)

### Trang thanh toán

> ✨ Điền đầy đủ thông tin mua hàng --> Nhận thông tin về phí vận chuyển 
>
 ![image](https://github.com/user-attachments/assets/1e73ab6a-40d3-494a-ab19-16a57c7aa2ab)

 ![image](https://github.com/user-attachments/assets/6c7bf8ae-8772-468e-841b-f5751ae553b8)


 ![image](https://github.com/user-attachments/assets/377af9c4-1d77-4dd7-8464-be58fd9dd2f2)

 ![image](https://github.com/user-attachments/assets/35f541f9-b54f-4389-9e6c-3fdc59445e23)

### Trang hồ sơ

### Trang thông tin / Đổi mật khẩu

![image](https://github.com/user-attachments/assets/28e88b1d-d0b8-4ceb-a913-b02017d89cbb)

> ✨ Chỉnh sửa thông tin cá nhân  
>
 ![image](https://github.com/user-attachments/assets/e65f2542-1a90-4478-92a5-9e97dab83aca)

 ![image](https://github.com/user-attachments/assets/6daae9da-759d-4daf-a7c0-54f0392b8e45)

> ✨ Đổi mật khẩu cho tài khoản 
>
 ![image](https://github.com/user-attachments/assets/8894052e-2552-468f-92fa-5d3b56f4421f)

> ✨ Quản lý các đơn mua hàng
>
![image](https://github.com/user-attachments/assets/ac939a68-c745-45d7-92ea-914fea142185)

> ✨ Yêu cầu đổi/trả sản phẩm sau khi mua
>
![image](https://github.com/user-attachments/assets/90499b40-85bd-41ac-b655-85389dce10b7)

> ✨ Theo dõi tình trạng của các sản phẩm đang được yêu cầu đổi/trả
>
![image](https://github.com/user-attachments/assets/6c26097d-50e5-453d-be18-614a1eabc9c1)

 
## 🌱 Trang dành cho Admin
### Trang đăng nhập
![image](https://github.com/user-attachments/assets/fe91761c-ac48-4dac-82c0-651cd6141554)

### Trang Dashboard
> ✨ Thống kê doanh số bán hàng, năng lực bán hàng, số lượng user mới hằng ngày
>
![image](https://github.com/user-attachments/assets/c8bd0f13-a777-4ad3-aa67-da21b5855ab5)

> ✨ Thống kê top các sản phẩm bán chạy
> 
![image](https://github.com/user-attachments/assets/da7a2cf9-584d-4082-8ab8-d52c84f05a1d)

### Trang quản lý đơn hàng
> ✨ Thống kê tổng doanh thu của các đơn hàng, thống kê số lượng đơn hàng theo từng loại
>
![image](https://github.com/user-attachments/assets/3a1733f7-9ed8-4ab4-bfea-489906ac5ffd)

> ✨ Theo dõi và cập nhật trạng thái các đơn hàng mới nhất
>
![image](https://github.com/user-attachments/assets/2c2abc9b-61d8-410b-862e-d657d1c53436)
![image](https://github.com/user-attachments/assets/3940545b-0c50-48e9-8c03-48b04a8a15f4)

> ✨ Khách hàng sẽ nhận được email thông báo khi đơn hàng được giao cho đơn vị vận chuyển
>
![image](https://github.com/user-attachments/assets/8a6925f6-6c40-41a4-8733-f4d3cc62e541)


> ✨ Bộ lọc, tìm kiếm, sắp xếp, cập nhật trạng thái của các đơn hàng
> 
![image](https://github.com/user-attachments/assets/93a93258-4ca1-433e-b07c-83e787e356ec)

### Trang quản lý sản phẩm

> ✨ Bộ lọc, tìm kiếm, sắp xếp, cập nhật trạng thái của các sản phẩm
> 
![image](https://github.com/user-attachments/assets/2eca0536-812d-4b39-a0a3-389821bc99eb)
![image](https://github.com/user-attachments/assets/f8995728-1422-4340-a416-d2b4adbe75a1)

> ✨ Cập nhật toàn bộ thông tin về một sản phẩm
> 
![image](https://github.com/user-attachments/assets/40213907-aa45-407f-a468-57f11986024d)

> ✨ Tìm kiếm/lọc mã giảm giá
> 
![image](https://github.com/user-attachments/assets/a27afb11-e4d1-4f66-81b8-adcf1747ce57)

> ✨ Cập nhật thông tin giới thiệu, loại, điều kiện áp dụng của mã giảm giá
> 
![image](https://github.com/user-attachments/assets/19618638-7321-4d76-9ec4-44391bbfd469)

### Trang quản lý yêu cầu đổi/trả hàng
> ✨ Thống kê số lượng yêu cầu đổi/trả hàng; thống kê các sản phẩm được yêu cầu đổi/trả nhiều nhất
> 
![image](https://github.com/user-attachments/assets/4f38675c-5da3-4f97-9143-2396057ca22c)

> Admin nhận được thông báo khi có yêu cầu đổi trả/hàng mới từ phía khách hàng
> 
![image](https://github.com/user-attachments/assets/30032061-9f53-4323-bb29-e99b96729a3b)

> Xem chi tiết yêu cầu đổi/trả hàng
>
![image](https://github.com/user-attachments/assets/648b564d-2e94-4075-8f69-c2219023c439)

> Chấp nhận/từ chối yêu cầu đổi/trả hàng
> 
![image](https://github.com/user-attachments/assets/8ee54602-5ad5-4222-ae3d-0d9d61c8df53)
![image](https://github.com/user-attachments/assets/57a6e0f4-7e7b-4fbc-b510-1a2cf2b56443)

> Khách hàng sẽ nhận được email thông báo nếu yêu cầu đổi/trả hàng được chấp nhận/từ chối
> 
![image](https://github.com/user-attachments/assets/c0eb2993-3e47-44c2-849d-f5acb762e082)
![image](https://github.com/user-attachments/assets/f703444c-f2f6-49b0-9cd3-365b3c304d28)

> Quản lý trạng thái của các yêu cầu đổi/trả hàng
> 
![image](https://github.com/user-attachments/assets/181620be-10bf-4bcb-9efb-bd21f2daab89)


## 7. Định hướng phát triển
### 7.1 Chức năng giỏ hàng, thanh toán 
- Hiển thị danh sách các Voucher hiện có của cửa hàng: Tạo một trang riêng biệt để hiển thị tất cả các voucher hiện có của cửa hàng, giúp người dùng dễ dàng tìm kiếm và sử dụng các ưu đãi phù hợp
- Gợi ý Voucher: Gợi ý các voucher phù hợp cho người dùng dựa trên các sản phẩm trong giỏ hàng của họ
- Tích hợp các phương thức thanh toán online (Banking): Hỗ trợ nhiều phương thức thanh toán trực tuyến như chuyển khoản ngân hàng, ví điện tử và các cổng thanh toán khác
 



 





 









    



