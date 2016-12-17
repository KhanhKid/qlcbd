Hướng Dẫn: 
@Cấu hình Database:
- host: localhost
- database name: qlcbd, qlcbd_website
- user: root
- password: 
@Triển Khai:
- import file "qlcbd.sql" để tạo database "qlcbd" và dữ liệu.(Chú ý: Nếu hệ thống tồn tại "qlcbd" trước thì db này sẽ bị xóa, vào thay đổi tên db trong file "qlcbd.sql" để lấy tên khác)
- import file "qlcbd_website.sql" để tạo database của website (thông tin người dùng trên site).
- nếu sử dụng như cấu hình trên thì có thể truy xuất csdl ngay, nếu khác thì thay đổi trong "config/autoload/global.php" của Website
@Sử dụng:
- Đăng nhập với username/password:
+ manager/123456: có thể thực hiện các thao tác giành cho người quản lý cán bộ
+ admin/admin: có thể thực hiện các thao tác giành cho người quản trị hệ thống website



MySQL 5.5 database added.  Please make note of these credentials:

       Root User: adminZyYMNQt
   Root Password: DHD6sbvLCR4G
   Database Name: qlcbd

Connection URL: mysql://$OPENSHIFT_MYSQL_DB_HOST:$OPENSHIFT_MYSQL_DB_PORT/

You can manage your new MySQL database by also embedding phpmyadmin.
The phpmyadmin username and password will be the same as the MySQL credentials above.