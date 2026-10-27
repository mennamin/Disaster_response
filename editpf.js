document.getElementById("pic").addEventListener("click",function()
{
    document.getElementById("file-pfp").click()
});


 document.getElementById("namepf").value="Peter Ben Parker";

document.getElementById("email").value="peterpareker@gmail.com";

document.getElementById("repid").addEventListener("click",function()

{
    document.getElementById("file-workid").click()
}

);

document.getElementById("address").value ="Nasr City";

function checkform()
{
   if (!checkid())
   {
    return false;
   }
   else
    if(!checkname())
    {
        return false;
    }
   if(!checkphone())
   {
    return false;
   }
}

function checkid()
{
    let idnum = document.getElementById("id-num");
        if (!/^[1-9]+$/.test(idnum.value))
        {
            alert("ID must be a positive integer");
            return false;
        }
        else
            return true;
};

function checkname()
{
    let name= document.getElementById("namepf");
        if(!/^[a-zA-Z\s]+$/.test(name.value))
        {
            alert("Enter a valid name");
            return false;
        }
        else
            return true;

}


function checkphone()
{
    let phone= document.getElementById("phone");
        if(!/^\+201[0-9]{9}$/.test(phone.value))
        {
            alert("Egyptian phone numb starting with + is req!");
            return false;
        }
        else
            return true;

}

document.getElementById("a").addEventListener("click",function()
{
    
    document.getElementById("time").textContent=document.getElementById("a").textContent;
});

document.getElementById("b").addEventListener("click",function()
{
    
    document.getElementById("time").textContent=document.getElementById("b").textContent;
});
