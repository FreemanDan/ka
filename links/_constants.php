<?

//#######################################################################
//#									#
//#		��������� �������� ������ FairLinks			#
//#									#
//#			(c) Kelkos 2006 alexey@zanevskiy.com		#
//#######################################################################

//��������� ����������� � MySQL
//���� ��� ������� �����������, �� �� ��������� ������� ������� ������� "Connection MySQL error!"
define("DBName","keramar8_links");		//��� ����
define("HostName","localhost"); 	//������
define("UserName","keramar8_root"); 		//������������
define("Password","freeman235");			//������


define("Base_Prefix","fl2_"); 			//������� MySQL ������ �����. ��� ��������� ���������� ��������� � ���� ���� ������ ���������� � ������ ������� ������ ��������!

//��������� ������� � ������ � �������
define("Admin_Login", 'Dan'); 				//����� ��� ������� � �������.
define("Admin_Password", 'forever');				//������ ��� ������� � �������.
define("Life_Time_Admin_Sess",60*60); 			//����� ������� � ������ ����������������� � ��������

define("Site_Name", "�����-��� - ������� ������"); 			//�������� ����� ��� �������� � �����. (������� � ���� "��")


//���� www � ������� ������ �� ����� �����.
//����� ��� ������������ ���������� url � �������.
//��������, ���� ������� ������ ��������� � ����� links � ����� �����, �� ���� ������� /links/
//���� ������� ������ ��������� � ����� �����, �� ����� ������� "/"
define("Global_WWW_Path","/links/"); 


//���������� ��� �������� � ��������� ������.
//��� ������� ����� � ����� tpl � ����� �������� �� ��������� fairlinks
//�� ��������� ������������ ������ "default"
//�������� ������� ������ ���� �������� ��� ������.
//��� ���������� � ������ ����� ���������� ������� ����������� ������ ��� ��������������.
//������ ��� �������������� ��������� ������ ���, ��� � ����� global.tpl ��� ���� ������, 
//�������, ����������������, ������ ���� � ����� �������� � ������� ������������ ������.
//�� ��������� � ��������� ��� ������ default_intgr
define("Use_Template","default_intgr"); 

//���� On/Off ����������� ��� ������������ url � ��������.
//���� ����� On - ������ ��������� url ��� ������������� mod_rewrite
//���� ����� Off - ���������� ����������� ����������� � �������� ������ ���������������.
//������������ ����������� ��� ��� ������������ ��������. �.�. �������� ������.
define("Use_Mod_Rewrite","On"); 


//�������������� � ������ ��������.
//�������� ����� ��������� �� ������ ����� �������� � ������� ���������� �������.
//�������� �� ���������� ������ ������ ��������� ������� ������� {FAIRLINKS_HERE} - ������ ������ ����� ����� ����� ������������ ������� ������.
//��� ��������� ����������� � ������� ����� ���������� ��������� �������� ������ global.tpl (��������, ������ <head> � ��. �.�. ��� ��� ���� � �������)
//����� ����� ��������� ��� ���� ��� ���� ������ ��� � �������� ������� (�������� {TITLE}) ��� ����������� ���� ������ � ��. �� �������� �������.
//��� �������������� � ������ ���������� ������� ������� �� �������������� � ������� ��� ���� ����� (TITLE � ��.). 
//�� ��������� � ��������� ��� ����� �������� default_intgr
define("Integrate_In_Page","http://www.keramart.com/node/614"); 	//�������� http://mysite.ru/index.php?go=links
//���� �������� �������� ������, �� ���������� ����������� �� �����.


//��������� ������ ����������� ���������� ��������.
//���������� ����������� � ���� "����� �������" ��� ���������� � ��������������.
$allow_img_ext=array();
$allow_img_ext[]='jpg';
$allow_img_ext[]='gif';
$allow_img_ext[]='png';


?>