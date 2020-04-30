# 文件上传失败 $_FILES tmp_name 为空

## references

> https://blog.csdn.net/zhanghw0917/article/details/46793847/

打印  tmp_name 为空

error_code 为 1 

超过了内存限制

需要在PHP.ini里设置以下几项:
1. post_max_size =10M  
表单提交最大数据为10M.此项不是限制上传单个文件的大小,而是针对整个表单的提交数据进行限制的.
限制范围包括表单提交的所有内容.例如:发表贴子时,贴子标题,内容,附件等...
2.file_uploads = On 
是否允许上传文件,如果为OFF您将不能上传文件.
3.upload_tmp_dir = "D:/APM/PHP/uploadtemp/" 
上传文件时系统使用的缓存目录.如果此目录所在磁盘空间不足的话您将不能上传文件.
4.upload_max_filesize =2M  
最大上传文件大小,此项针对上传文件时单个文件的大小.
————————————————
版权声明：本文为CSDN博主「黑太岁」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。
原文链接：https://blog.csdn.net/zhanghw0917/article/details/46793847/