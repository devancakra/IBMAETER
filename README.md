[![Open Source Love](https://badges.frapsoft.com/os/v1/open-source.svg?style=flat)](https://github.com/ellerbrock/open-source-badges/)
[![License: BCD 3-Clause](https://img.shields.io/badge/License-BSD%203%2D-Clause-blue.svg?logo=github&color=%23F7DF1E)](https://opensource.org/license/bsd-3-clause)
![GitHub last commit](https://img.shields.io/github/last-commit/cakraawijaya/IBMAETER?logo=Codeforces&logoColor=white&color=%23F7DF1E)
![Project](https://img.shields.io/badge/Project-Website-light.svg?style=flat&logo=googlechrome&logoColor=white&color=%23F7DF1E)
![Type](https://img.shields.io/badge/Type-IPR-light.svg?style=flat&logo=gitbook&logoColor=white&color=%23F7DF1E)

# IBMAETER
<strong>IPR Project - Computer Programs</strong><br><br>
IBMAETER is a website designed to streamline warehouse inventory and workforce management. Its features allow businesses to maintain full control over stock levels, product prices, weights, and more, all delivered quickly and in real time. This eliminates the need for manual processes, helping users save valuable time and improve overall business performance.

<br><br>

## Project Requirements
| Part | Description |
| --- | --- |
| Features | • Login<br>• Create<br>• Read<br>• Update<br>• Delete<br>• Pagination<br>• Search<br>• Validation<br>• Print<br>• Export<br>• ETC |
| Framework | • Bootstrap 4<br>• CodeIgniter 4<br>• Font-Awesome |
| Tools | • Visual Studio Code<br>• Xampp<br>• Git |

<br><br>

## Download & Install
1. XAMPP with PHP version 7.4

   <table><tr><td width="810">

   ```
   https://bit.ly/XAMPP_PHP7_Installer
   ```
   
   </td></tr></table><br>

2. Visual Studio Code

   <table><tr><td width="810">

   ```
   https://bit.ly/VScode_Installer
   ```
   
   </td></tr></table><br>

3. Git

   <table><tr><td width="810">
   
   ```
   https://bit.ly/GIT_Installer
   ```
   
   </td></tr></table>

<br><br>

## Database
1. Open ``` XAMPP ```, then start the ``` Apache ``` & ``` MySQL ``` section to run a web server and a database server locally.<br><br>

2. Access the browser first in order to open the database admin panel, please copy the following link: ``` localhost/phpmyadmin/ ```.<br><br>

3. Create a database called ``` warehouse_db ``` on local.<br><br>

4. Open the ``` warehouse_db ``` database and Import ``` warehouse_db_default.sql ``` in the ``` IBMAETER/public/sql ``` directory.<br><br>

5. If the database is not working properly, you can import the ``` triggers ``` provided by the application creator in the ``` IBMAETER/public/sql/trigger ``` directory.<br><br>

6. Then open the XAMPP file: ``` php.ini ``` -> remove ``` semicolon (;) ``` in front of ``` extension=intl ``` -> save.

<br><br>

## Default Account
| Role | Email | Password |
| --- | --- | --- |
| Admin | af@gmail.com | Superadmin123 |
| User | adeline@gmail.com | User123456 |
| User | renaldyy@gmail.com | User123456 |

<br><br>

## Get Started
1. Download and extract this repository.<br><br>

2. Open the ``` IBMAETER ``` directory, then open ``` GitBash ``` inside that directory.<br><br>
   
   • First, check whether there is a problem or not, if there is still an error, just adjust it according to the situation.<br><br>
   
   • Secondly, this step is mandatory, so please copy the following command:

   <table><tr><td width="810">
      
   ````bash
   php spark serve
   ````
   
   </td></tr></table><br>

3. Open your ``` browser ``` (New tab), then type -> ``` localhost:8080 ``` or customize the one on your ``` GitBash ```.<br><br>
   
4. Please login and access the features, enjoy [Done].

<br><br>

## Highlights
<table>
<tr>
<th width="280">Home</th>
<th width="280">Policies</th>
<th width="280">Scam Alert</th>
</tr>
<tr>
<td><img src="documentation/images/Home.jpg" alt="home"></td>
<td><img src="documentation/images/Policies.jpg" alt="policies"></td>
<td><img src="documentation/images/Scam Alert.jpg" alt="scam-alert"></td>
</tr>
</table>
<table>
<tr>
<th width="840">Login</th>
</tr>
<tr>
<td><img src="documentation/images/Login.jpg" alt="login"></td>
</tr>
</table>
<table>
<tr>
<th colspan="2">Dashboard</th>
</tr>
<tr>
<td width="420"><img src="documentation/images/Dashboard-1.jpg" alt="dashboard-1"></td>
<td width="420"><img src="documentation/images/Dashboard-2.jpg" alt="dashboard-2"></td>
</tr>
</table>
<table>
<tr>
<th width="280">Add Workers</th>
<th width="280">Edit Workers</th>
<th width="280">Item Authorization</th>
</tr>
<tr>
<td><img src="documentation/images/Add Workers.jpg" alt="add-workers"></td>
<td><img src="documentation/images/Edit Workers.jpg" alt="edit-workers"></td>
<td><img src="documentation/images/Item Authorization.jpg" alt="item-authorization"></td>
</tr>
</table>
<table>
<tr>
<th width="280">Manage Items</th>
<th width="280">Print Feature</th>
<th width="280">Attendance</th>
</tr>
<tr>
<td><img src="documentation/images/Manage Items.jpg" alt="manage-items"></td>
<td><img src="documentation/images/Print Feature.jpg" alt="print-feature"></td>
<td><img src="documentation/images/Attendance.jpg" alt="attendance"></td>
</tr>
</table>
<table>
<tr>
<th width="280">User Track Record</th>
<th width="280">Edit Profile</th>
<th width="280">Announcements</th>
</tr>
<tr>
<td><img src="documentation/images/User Track Record.jpg" alt="user-track-record"></td>
<td><img src="documentation/images/Edit Profile.jpg" alt="edit-profile"></td>
<td><img src="documentation/images/Announcements.jpg" alt="announcements"></td>
</tr>
</table>
<table>
<tr>
<th width="840">Edit Announcements</th>
</tr>
<tr>
<td><img src="documentation/images/Edit Announcements.jpg" alt="edit-announcements"></td>
</tr>
</table>

<br><br>

## IBMAETER Project Team Members
| MEMBER | FULL NAME |
| --- | --- |
| 1 | Rizky Parlika, S.Kom., M.Kom. |
| 2 | Rifky Akhmad Fernanda |
| 3 | Devan Cakra Mudra Wijaya |
| 4 | Merdin Risalul Abrori |
| 5 | Arista Pratama, S.Kom., M.Kom. |
| 6 | Lugito Michael Imanuel Prasetya, S.Kom. |

<br><br>

## Reminder
If the database auto-increment is still not in order, then you can do the following in phpMyAdmin:

<table><tr><td width="840">
   
```sql
SET  @num := 0;
UPDATE your_table SET id = @num := (@num+1);
ALTER TABLE your_table AUTO_INCREMENT =1;
```

</td></tr></table>

<br><br>

## Appreciation
If this work is useful to you, then support this work as a form of appreciation to the author by clicking the ``` ⭐Star ``` button at the top of the repository.

<br><br>

## Disclaimer
This application is the result of my work with my team and is not the result of plagiarism from other people's research or work, except those related to third party services which include: libraries, frameworks, and so on.

<br><br>

## LICENSE
BSD 3 CLAUSE LICENSE - Copyright © 2022 - Devan C. M. Wijaya et al

Dissemination and use in source and binary form, with or without with or without modification, is allowed provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation, and/or other materials included with the distribution.

3. Neither the copyright holder's name nor the names of its contributors may be used to endorse or promote products derived from this software without prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. NEITHER THE COPYRIGHT HOLDER NOR THE CONTRIBUTORS SHALL BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING BUT NOT LIMITED TO PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND UNDER ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY FROM THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.
