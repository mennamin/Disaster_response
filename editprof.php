<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="prof.css">
      <link rel="stylesheet" href="editpf.css">
       <script src="editpf.js" defer></script>
</head>
<body>
    <form onsubmit="return checkform()">
   <button type="submit">Update Profile</button>
 <div class="big-card">
 
 
   <div class="pic-n-name">
        <img id ="pic" src="images\spiderman.jpg" alt ="pfp">
        

        <input type="file" id="file-pfp">
         <div class="name-card"> 
         <input type="text" id="namepf" placeholder="peter parker">
              <div class="role-card">
             
             <select id="role">
            <option value="reporter">Reporter</option>
            <option value="incm">Incident Managment</option>
            <option value="dept">Department</option>
            </select>
             </div>
            <div class="small-txt">
             <input type="email" id="email" placeholder="peterparker@gmail.com">    
           </div>
      </div>
  </div>

     <div id="info-parent">
       <div class="info-card" id="infc1">
          <div class="txt-box">
             <div class="txt-style">WORK ID: </div>
          </div>
         <img id ="repid" src="images\repID.png" alt ="Reporter ID">
           <input type="file" id="file-workid">
       </div>
       <div class="info-card" id="infc2">
            <div class="txt-box txt-style2">
             <div class="txt-style ">WORK INFORMATION: </div>
          </div>
          <div class="long-box">
            <div class="txt-style down"><B>Reporter ID:</b>
              <input type="text" id="id-num">
        </div>
             <div class="txt-style txt5 down"><b>Joined Since: </b>
            <input type="date" id="birthdate" min="2020-1-1" max="2026-10-28" required>
            </div>
             <div class="txt-style  stat down"><b>Status:</b></div>
               <div class="txt-box stat">
                <div class="dot"></div>
                 <div class="txt-style status">ACTIVE</div>
               </div> 
            </div>
          <div id="gen-info">

        </div>

       </div>
     </div>
       <div id="info-parent2">
         <div class="txt-box txt-style2">
<div class="txt-style ">EMPLOEYEE INFORMATION: </div>

</div><br><hr>
<div class="wide-left">
            <div class="txt-style down g"><B>Gender:</b>
            
            <label> 
              <div class="words">  Male </div>
           <input type="radio" id="male" name="gender" value="male" required>
           </label>
           <label>
            <div class="words">  Female </div>
           <input type="radio" id="female" name="gender" value="female">
        
            </label>
            </div>
              <div class="txt-style down side add"><b>Address:</b> <input type="text" id="address" ></div>
             <div class="txt-style txt5 down "><b>Date of Birth: </b>
             <input  type="date" id="birthdate" min="1940-1-1" max="2007-1-1" required>
            </div>
               <div class="txt-style down side"><b>Phone Number: </b>
              <input type="tel" id="phone" name="phone" value="+201123456789">
            </div>
           

 </div>
 <br>
<hr>
<div class="wide-col">
   <div class="shift">
             <div class="txt-style down s shift"><B>Shift: </b> <label> 
                 
              <div class="words shift">Night</div>
           <input type="radio" id="night" name="shift" value="night" required>
           </label>
           <label>
            <div class="words shift">Morning</div>
           <input type="radio" id="morning" name="shift" value="morning">
        </div>
</div>
             <div class="txt-style down s">
                  <div class="dropdown">
                 <button class="dropbtn" id="time">Teusday-thursday</button>
                   <div class="dropdown-content">
                       <div class="option" id="a" >Teusday-thursday</div>
                        <div class="option" id="b" >Friday-Monday</div>
                   </div>
                </div>

            
             <div class="txt-style down s"><b>Number Of Reports Filed: </b>3930</div>
       </div>


</div>

</div>

</form>
</body>
</html>