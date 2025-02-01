const  progressbar=document.getElementById('progressbar');
const progressbarValue=document.getElementById('progressbarValue');
const barValue=document.getElementById('barvalue');
let totalSales=`${'<?php echo $mysqli->query("select id from e_product_details pd where pd.is_active = 1")->num_rows;?>'}`;
console.log(totalSales);
let progressStart=0;
let progressEnd=60;
speed=100;
let progress=setInterval(()=>{
    progressStart++;
    barValue.textContent=`${progressStart}%`;
    progressbar.style.background=`conic-gradient(rgba(59, 0, 228, 1) ,rgba(55, 147, 255, 1) ${progressStart * 3.6}deg, rgba(252, 252, 252, 1) 0deg)`;
    if(progressStart==progressEnd){
        clearInterval(progress);
    }
},speed);

const config = {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    datasets: [{
      label: 'Turnover',
      data: [5, 12, 15, 45, 25, 32, 35, 40, 67, 20, 30, 69],
      backgroundColor: ['rgba(241, 120, 182, 1)','rgba(120, 121, 241, 1)','rgba(120, 234, 241, 1)','rgba(241, 120, 120, 1)','rgba(120, 139, 241, 1)','rgba(120, 241, 183, 1)','rgba(241, 193, 120, 1)','rgba(120, 241, 226, 1)','rgba(188, 120, 241, 1)','rgba(210, 241, 120, 1)','rgba(120, 168, 241, 1)','rgba(239, 241, 120, 1)'],
      borderColor: ['rgba(241, 120, 182, 1)','rgba(120, 121, 241, 1)','rgba(120, 234, 241, 1)','rgba(241, 120, 120, 1)','rgba(120, 139, 241, 1)','rgba(120, 241, 183, 1)','rgba(241, 193, 120, 1)','rgba(120, 241, 226, 1)','rgba(188, 120, 241, 1)','rgba(210, 241, 120, 1)','rgba(120, 168, 241, 1)','rgba(239, 241, 120, 1)'],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: false,
        ticks: {
          callback: function(value) {
            return value + 'L';
          }
        }
      }
    }
  }
};

const ctx = document.getElementById('myChart').getContext('2d');
new Chart(ctx, config);