<?php

// sucess response code
$success=[];
$success['status_code']=200;
$success['message']="OK";

// not_found response code
$not_found=[];
$not_found['status_code']=404;
$not_found['message']="Page not found1";

// un_authorised response code
$un_authorised=[];
$un_authorised['status_code']=401;
$un_authorised['message']="unauthorised page";

// forbidden response code
$forbidden=[];
$forbidden['status_code']=403;
$forbidden['message']="forbidden";

// created response code
$created=[];
$created['status_code']=201;
$created['message']="created succesfully";

// not_created response code
$not_created=[];
$not_created['status_code']=400;
$not_created['message']="Bad Request";

// no_data response code
$no_data=[];
$no_data['status_code']=400;
$no_data['message']="no data found !";

$no_data=[];
$no_data['status_code']=400;
$no_data['message']="error. no data found";

$no_data_found=[];
$no_data_found['status_code']=200;
$no_data_found['message']="no data found";

$no_data_delete=[];
$no_data_delete['status_code']=400;
$no_data_delete['message']="no data deleted";

$delete_success=[];
$delete_success['status_code']=200;
$delete_success['message']="deleted data succesfully";
?>