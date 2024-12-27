<div align="center">
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/192158954-f88b5814-d510-4564-b285-dff7d6400dad.png" alt="HTML" title="HTML"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/183898674-75a4a1b1-f960-4ea9-abcb-637170a00a75.png" alt="CSS" title="CSS"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/183898054-b3d693d4-dafb-4808-a509-bab54cf5de34.png" alt="Bootstrap" title="Bootstrap"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/117447155-6a868a00-af3d-11eb-9cfe-245df15c9f3f.png" alt="JavaScript" title="JavaScript"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/183570228-6a040b9f-3ddf-47a2-a201-743121dac664.png" alt="php" title="php"/></code>
	<code><img width="30" src="https://github.com/marwin1991/profile-technology-icons/assets/25181517/afcf1c98-544e-41fb-bf44-edba5e62809a" alt="Laravel" title="Laravel"/></code>
	<code><img width="30" src="https://user-images.githubusercontent.com/25181517/183896128-ec99105a-ec1a-4d85-b08b-1aa1620b2046.png" alt="MySQL" title="MySQL"/></code>
</div>

# PLANT PARADISE
Bạn đang tìm kiếm một góc xanh tươi để thư giãn và tận hưởng cuộc sống? **Plant Paradise** chính là thiên đường mà bạn đang tìm kiếm! Với vô vàn giống cây xanh độc đáo, từ những chậu cây nhỏ xinh đến những cây cảnh   lớn, chúng tôi cam kết mang đến cho bạn một không gian sống xanh mát và tràn đầy sức sống. Hãy để **Plant Paradise** giúp bạn tạo nên một thiên đường xanh ngay tại ngôi nhà của mình!

## 1. Giới thiệu đồ án môn học 
- **Tên môn học**: Phát triển ứng dụng WEB
- **Mã lớp**: IS207.P11
- **Tên đồ án**: Website thương mại điện tử cho cửa hàng bán cây cảnh **Plant Paradise**

## 2. Nhóm thực hiện
**Tên nhóm: Coconerd** 🥥

| Họ và tên          | MSSV     | Vai trò     | Liên hệ                     |
|--------------------|----------|-------------|-----------------------------|
|🌱  Nguyễn Đỗ Đức Minh | 2252xxxx | Team lead   | example1@gmail.com          |
|🌱  Phan Thị Thủy Hiền | 2252xxxx | Team member | thuyhienphanthi2004@gmail.com |
|🌱  Phan Thành Công   | 2252xxxx | Team member | example3@gmail.com          |
|🌱  Trần Vũ Bão       | 2252xxxx | Team member | example4@gmail.com          |

## 3. Thiết kế Cơ sở Dữ Liệu 
[dbdiagram.io](https://dbdiagram.io/d/Plant-Paradise-Database-672671edb1b39dd85843f893)

![image](https://github.com/user-attachments/assets/635edd76-679f-49d3-9084-0468eb105189)

## 4. Qui trình nghiệp vụ

### 4.1 Chính sách bán hàng
- Về việc áp dụng Voucher: Mỗi đơn mua hàng chỉ được áp dụng 1 loại Voucher
- Về chính sách giao hàng:
    - Các sản phẩm cây đi kèm chậu: Chỉ nhận giao nội thành TP.HCM
    - Các sản phẩm chậu: Giao hàng toàn quốc  
- Về qui định phí giao hàng:
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
<strong>Trang web chính</strong>
├── <a href="#61-Trang-đăng-kí--đăng-nhập">Đăng kí / đăng nhập</a>
│  
├── <a href="#62-Trang-chủ">Trang chủ</a>
│   │
│   ├── <a href="#621-Trang-danh-mục-sản-phẩm">Trang danh mục sản phẩm</a>
│       │
│       │── <a href="#6211-Trang-chi-tiết-sản-phẩm">Trang chi tiết sản phẩm</a>
│  
├── <a href="#63-Trang-giỏ-hàng">Trang giỏ hàng</a>
│   │
│   │── <a href="#631-Trang-thanh-toán">Trang thanh toán</a>
│   
└── <a href="#64-Trang-hồ-sơ">Trang hồ sơ</a>
    │
    └── <a href="#641-Trang-hồ-sơ--đổi-mật-khẩu">Trang thông tin / đổi mật khẩu</a>
    │    
    └── <a href="#642-Trang-">Trang .....</a>

<strong>Trang dành cho Admin</strong>
├── <a href="#dashboard">Dashboard</a>
│    
└── <a href="#branch-b">Branch B</a>
    └── <a href="#sub-branch-b1">Sub-branch B.1</a>
        └── <a href="#leaf-b11">Leaf B.1.1</a>
</pre>

### 6.1 Trang đăng kí / đăng nhập
Đăng kí với Email

![image](https://github.com/user-attachments/assets/5571fb0d-0420-4ea8-a325-b2b90e6b6436)

Đăng nhập
- Đăng nhập với Email
- Đăng nhập với Google
- Đăng nhập với Facebook

![image](https://github.com/user-attachments/assets/617cf64f-b6a5-4a7f-8605-5630128bdfc4)

### 6.2 Trang chủ

![image](https://github.com/user-attachments/assets/f8d86e84-1f5c-444e-903f-fe853cfdacc4)
    
![image](https://github.com/user-attachments/assets/57e8d5bd-4b61-4674-a5b6-7d1e6559429c)
    
![image](https://github.com/user-attachments/assets/e629a937-1847-4ae7-87f2-02b2d8b32879)
    
![image](https://github.com/user-attachments/assets/b4780b9f-4e82-4ce0-9855-1e6833468e4b)
    
![image](https://github.com/user-attachments/assets/81fa7d78-162a-4d5d-8439-62060cb2e0a1)

> ✨ Tìm kiếm sản phẩm
> 
 ![image](https://github.com/user-attachments/assets/9c989814-03b8-41f2-b279-bb025018e00a)


#### 6.2.1 Trang danh mục sản phẩm

![image](https://github.com/user-attachments/assets/d878a5b7-a929-45f9-baff-7c47f17351d2)

![image](https://github.com/user-attachments/assets/b5b35c83-4b16-48a9-ade7-0cf5262d4619)

> ✨ Chọn danh mục sản phẩm
> 
 ![image](https://github.com/user-attachments/assets/73d25573-7f7a-4855-ad0f-b6ed39749de7)

> ✨ Chọn khoảng giá thành mong muốn
> 
 ![image](https://github.com/user-attachments/assets/8919dd8f-7ad2-4e31-905c-0e56aa1ce6e0)
 
> ✨ Filter sản phẩm
> 

##### 6.2.1.1 Trang chi tiết sản phẩm

![image](https://github.com/user-attachments/assets/d7204d91-a51d-410c-8465-1eb6d6991cf0)
> ✨ Danh sách các sản phẩm tương tự
>
 ![image](https://github.com/user-attachments/assets/b5bb1442-ed9b-4d6d-bfee-ae82e73693db)

> ✨ Danh sách các sản phẩm đang khuyến mãi
>
 ![image](https://github.com/user-attachments/assets/3a45ae80-b8fb-44e3-bf07-2aec29f22b3e)

> ✨ Danh sách Feedback của các khách hàng đã sản phẩm 
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

### 6.3 Trang giỏ hàng 

![image](https://github.com/user-attachments/assets/0b98057c-f810-4ccc-8632-ef42bdfb83a8)

> ✨ Xóa sản phẩm ra khỏi giỏ hàng
>
 ![image](https://github.com/user-attachments/assets/30fd3d6a-26b8-4d00-b552-67c516ed5071)

 ![image](https://github.com/user-attachments/assets/08a72c0c-46b1-481e-9290-6d981fb8ef7c)

 ![image](https://github.com/user-attachments/assets/8949796b-17f7-4ebb-8ca8-268692f6d25e)


> ✨ Áp dụng Voucher
>
 ![image](https://github.com/user-attachments/assets/5fa2fab4-6626-4628-8003-a7c90819e0ac)

 ![image](https://github.com/user-attachments/assets/4c2cdbb0-0d72-48e9-8d40-2c824bce2165)

 ![image](https://github.com/user-attachments/assets/cf59dc21-8c6b-43cd-b60e-041cdef3438b)

#### 6.3.1 Tranh thanh toán

> ✨ Điền thông tin mua hàng > nhận thông tin về phí Ship
>
 ![image](https://github.com/user-attachments/assets/1e73ab6a-40d3-494a-ab19-16a57c7aa2ab)

 ![image](https://github.com/user-attachments/assets/6c7bf8ae-8772-468e-841b-f5751ae553b8)


 ![image](https://github.com/user-attachments/assets/377af9c4-1d77-4dd7-8464-be58fd9dd2f2)

 ![image](https://github.com/user-attachments/assets/35f541f9-b54f-4389-9e6c-3fdc59445e23)

### 6.4 Trang hồ sơ
#### 6.4.1 Trang thông tin / đổi mật khẩu

> ✨ Thông tin khách hàng 
>
 ![image](https://github.com/user-attachments/assets/ff4268ed-63df-49fe-96f4-4ee02d4be387)

 ![image](https://github.com/user-attachments/assets/6daae9da-759d-4daf-a7c0-54f0392b8e45)

> ✨ Đổi mật khẩu 
>
 ![image](https://github.com/user-attachments/assets/8894052e-2552-468f-92fa-5d3b56f4421f)



 





 









    



