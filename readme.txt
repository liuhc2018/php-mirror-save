1.server:
delete_file.php : delete a file in target dir.
exist_file.php : return whether a file exists in target dir.
upload_file.php: upload file to target dir.

2.client:
client_const.php : const value defined here.

asyn_file_upload_client.php : upload file to server asynchoronous.
file_client.php : offer functions such as saveFile, getFile, deleteFile.
client_demo.php: demo case.

clean_local_dir.php: clean least used files in local dir.

3.client_const.php:
remoteDestDir denotes remote default dir path storing file. It should be consistent with upDir in upload_file.php on server side!!!
remoteIp denotes remote server IP.
localDestDir denotes local dir path.
maxSize denote max size of local dir path.