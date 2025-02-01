window.addEventListener('pageshow', (event) => {
  console.log(event.persisted);
  console.log(window.performance);
  console.log(window.performance.navigation.type);
  if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
    uncheckAll();
  }
});

function uncheckAll() {
  checkHead.checked = false; 
  checkValues.forEach(checkValue => checkValue.checked = false);
  actionSect.style.display = 'none';
  editIcon.style.display = 'inline';
} 

const checkValues = Array.from(document.getElementsByClassName('check_value'));
console.log(checkValues);
const actionSect = document.querySelector('.action_sect');
const editIcon = document.getElementById('editIcon');

let selectedRowId = null;
let selectedBatchValue = null;

checkValues.forEach((checkValue) => {
  checkValue.addEventListener("click", displayCheck);
});



function displayCheck() {
  let checkedCount = 0;
  const checkedRowIds = [];
  const selectedBatchValues = [];

  checkValues.forEach(checkValue => {
    if (checkValue.checked) {
      checkedCount++;
      const rowId = checkValue.getAttribute('data-id');
      console.log(rowId);
      const batchValue = checkValue.getAttribute('data-batch') || null;
      checkedRowIds.push(rowId);
      selectedBatchValues.push(batchValue);
    }
  });

  if (checkedCount > 1) {
    editIcon.style.display = 'none';
  } else {
    editIcon.style.display = 'inline';
  }

  if (checkedCount > 0) {
    actionSect.style.display = 'block';
  } else {
    actionSect.style.display = 'none';
  }
  console.log(checkedRowIds,selectedBatchValues);
  return { checkedRowIds, selectedBatchValues };
}

const checkHead = document.querySelector(".checkbox_thead");
const checkDef = document.querySelectorAll(".checkbox_tdef");

checkHead.addEventListener('change', e => {
  if (checkHead.checked) {
    checkDef.forEach(checkValue => checkValue.checked = true);
  } else {
    checkDef.forEach(checkValue => checkValue.checked = false);
  }
  displayCheck();
});

//popup 
document.addEventListener('DOMContentLoaded', function() {
const popup=document.getElementById("popup");
const cancelBtn=document.getElementById("cancel_btn_<?php echo $row1['id'];?>");

const deletePopup=document.getElementById("delete_popup");
const deleteBtn=document.querySelector(".delete_icon");
const deleteCancelBtn=document.getElementById("del_cancel_btn");
const container=document.querySelector('.container');

deleteBtn.addEventListener("click",()=>{
  deletePopup.classList.add("open_popup");
  container.classList.toggle("active");
});

deleteCancelBtn.addEventListener("click",()=>{
  deletePopup.classList.remove("open_popup");
  container.classList.toggle("active");
});
});
