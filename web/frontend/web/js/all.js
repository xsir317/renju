/*2024-10-07 19:44:34*/
/* page.js */

 const faces = {"map":{"QQ000":"faces\/qq_default\/MRF_100000.gif","QQ001":"faces\/qq_default\/MRF_100001.gif","QQ002":"faces\/qq_default\/MRF_100002.gif","QQ003":"faces\/qq_default\/MRF_100003.gif","QQ004":"faces\/qq_default\/MRF_100004.gif","QQ005":"faces\/qq_default\/MRF_100005.gif","QQ006":"faces\/qq_default\/MRF_100006.gif","QQ007":"faces\/qq_default\/MRF_100007.gif","QQ008":"faces\/qq_default\/MRF_100008.gif","QQ009":"faces\/qq_default\/MRF_100009.gif","QQ010":"faces\/qq_default\/MRF_100010.gif","QQ011":"faces\/qq_default\/MRF_100011.gif","QQ012":"faces\/qq_default\/MRF_100012.gif","QQ013":"faces\/qq_default\/MRF_100013.gif","QQ014":"faces\/qq_default\/MRF_100014.gif","QQ015":"faces\/qq_default\/MRF_100015.gif","QQ016":"faces\/qq_default\/MRF_100016.gif","QQ017":"faces\/qq_default\/MRF_100017.gif","QQ018":"faces\/qq_default\/MRF_100018.gif","QQ019":"faces\/qq_default\/MRF_100019.gif","QQ020":"faces\/qq_default\/MRF_100020.gif","QQ021":"faces\/qq_default\/MRF_100021.gif","QQ022":"faces\/qq_default\/MRF_100022.gif","QQ023":"faces\/qq_default\/MRF_100023.gif","QQ024":"faces\/qq_default\/MRF_100024.gif","QQ025":"faces\/qq_default\/MRF_100025.gif","QQ026":"faces\/qq_default\/MRF_100026.gif","QQ027":"faces\/qq_default\/MRF_100027.gif","QQ028":"faces\/qq_default\/MRF_100028.gif","QQ029":"faces\/qq_default\/MRF_100029.gif","QQ030":"faces\/qq_default\/MRF_100030.gif","QQ031":"faces\/qq_default\/MRF_100031.gif","QQ032":"faces\/qq_default\/MRF_100032.gif","QQ033":"faces\/qq_default\/MRF_100033.gif","QQ034":"faces\/qq_default\/MRF_100034.gif","QQ035":"faces\/qq_default\/MRF_100035.gif","QQ036":"faces\/qq_default\/MRF_100036.gif","QQ037":"faces\/qq_default\/MRF_100037.gif","QQ038":"faces\/qq_default\/MRF_100038.gif","QQ039":"faces\/qq_default\/MRF_100039.gif","QQ040":"faces\/qq_default\/MRF_100040.gif","QQ041":"faces\/qq_default\/MRF_100041.gif","QQ042":"faces\/qq_default\/MRF_100042.gif","QQ043":"faces\/qq_default\/MRF_100043.gif","QQ044":"faces\/qq_default\/MRF_100044.gif","QQ045":"faces\/qq_default\/MRF_100045.gif","QQ046":"faces\/qq_default\/MRF_100046.gif","QQ047":"faces\/qq_default\/MRF_100047.gif","QQ048":"faces\/qq_default\/MRF_100048.gif","QQ049":"faces\/qq_default\/MRF_100049.gif","QQ050":"faces\/qq_default\/MRF_100050.gif","QQ051":"faces\/qq_default\/MRF_100051.gif","QQ052":"faces\/qq_default\/MRF_100052.gif","QQ053":"faces\/qq_default\/MRF_100053.gif","QQ054":"faces\/qq_default\/MRF_100054.gif","QQ055":"faces\/qq_default\/MRF_100055.gif","QQ056":"faces\/qq_default\/MRF_100056.gif","QQ057":"faces\/qq_default\/MRF_100057.gif","QQ058":"faces\/qq_default\/MRF_100058.gif","QQ059":"faces\/qq_default\/MRF_100059.gif","QQ060":"faces\/qq_default\/MRF_100060.gif","QQ061":"faces\/qq_default\/MRF_100061.gif","QQ062":"faces\/qq_default\/MRF_100062.gif","QQ063":"faces\/qq_default\/MRF_100063.gif","QQ064":"faces\/qq_default\/MRF_100064.gif","QQ065":"faces\/qq_default\/MRF_100065.gif","QQ066":"faces\/qq_default\/MRF_100066.gif","QQ067":"faces\/qq_default\/MRF_100067.gif","QQ068":"faces\/qq_default\/MRF_100068.gif","QQ069":"faces\/qq_default\/MRF_100069.gif","QQ070":"faces\/qq_default\/MRF_100070.gif","QQ071":"faces\/qq_default\/MRF_100071.gif","QQ072":"faces\/qq_default\/MRF_100072.gif","QQ073":"faces\/qq_default\/MRF_100073.gif","QQ074":"faces\/qq_default\/MRF_100074.gif","QQ075":"faces\/qq_default\/MRF_100075.gif","QQ076":"faces\/qq_default\/MRF_100076.gif","QQ077":"faces\/qq_default\/MRF_100077.gif","QQ078":"faces\/qq_default\/MRF_100078.gif","QQ079":"faces\/qq_default\/MRF_100079.gif","QQ080":"faces\/qq_default\/MRF_100080.gif","tuzki_01":"faces\/tuzki\/01.gif","tuzki_02":"faces\/tuzki\/02.gif","tuzki_03":"faces\/tuzki\/03.gif","tuzki_04":"faces\/tuzki\/04.gif","tuzki_05":"faces\/tuzki\/05.gif","tuzki_06":"faces\/tuzki\/06.gif","tuzki_07":"faces\/tuzki\/07.gif","tuzki_08":"faces\/tuzki\/08.gif","tuzki_09":"faces\/tuzki\/09.gif","tuzki_10":"faces\/tuzki\/10.gif","tuzki_11":"faces\/tuzki\/11.gif","tuzki_12":"faces\/tuzki\/12.gif","tuzki_13":"faces\/tuzki\/13.gif","tuzki_14":"faces\/tuzki\/14.gif","tuzki_15":"faces\/tuzki\/15.gif","tuzki_16":"faces\/tuzki\/16.gif","tuzki_17":"faces\/tuzki\/17.gif","tuzki_18":"faces\/tuzki\/18.gif","tuzki_19":"faces\/tuzki\/19.gif","tuzki_20":"faces\/tuzki\/20.gif","tuzki_21":"faces\/tuzki\/21.gif","tuzki_22":"faces\/tuzki\/22.gif","tuzki_23":"faces\/tuzki\/23.gif","tuzki_24":"faces\/tuzki\/24.gif","tuzki_25":"faces\/tuzki\/25.gif","tuzki_26":"faces\/tuzki\/26.gif","tuzki_27":"faces\/tuzki\/27.gif","tuzki_28":"faces\/tuzki\/28.gif","tuzki_29":"faces\/tuzki\/29.gif","tuzki_30":"faces\/tuzki\/30.gif","tuzki_31":"faces\/tuzki\/31.gif","tuzki_32":"faces\/tuzki\/32.gif","tuzki_33":"faces\/tuzki\/33.gif","tuzki_34":"faces\/tuzki\/34.gif","tuzki_35":"faces\/tuzki\/35.gif","tuzki_36":"faces\/tuzki\/36.gif","tuzki_37":"faces\/tuzki\/37.gif","tuzki_38":"faces\/tuzki\/38.gif","qbl001":"faces\/qiubilong\/001.gif","qbl002":"faces\/qiubilong\/002.gif","qbl003":"faces\/qiubilong\/003.gif","qbl004":"faces\/qiubilong\/004.gif","qbl005":"faces\/qiubilong\/005.gif","qbl006":"faces\/qiubilong\/006.gif","qbl007":"faces\/qiubilong\/007.gif","qbl008":"faces\/qiubilong\/008.gif","qbl009":"faces\/qiubilong\/009.gif","qbl010":"faces\/qiubilong\/010.gif","qbl011":"faces\/qiubilong\/011.gif","qbl012":"faces\/qiubilong\/012.gif","qbl013":"faces\/qiubilong\/013.gif","qbl014":"faces\/qiubilong\/014.gif","qbl015":"faces\/qiubilong\/015.gif","qbl016":"faces\/qiubilong\/016.gif","qbl017":"faces\/qiubilong\/017.gif","qbl018":"faces\/qiubilong\/018.gif","qbl019":"faces\/qiubilong\/019.gif","qbl020":"faces\/qiubilong\/020.gif","qbl021":"faces\/qiubilong\/021.gif","qbl022":"faces\/qiubilong\/022.gif","qbl023":"faces\/qiubilong\/023.gif","qbl024":"faces\/qiubilong\/024.gif","qbl025":"faces\/qiubilong\/025.gif","qbl026":"faces\/qiubilong\/026.gif","qbl027":"faces\/qiubilong\/027.gif","qbl028":"faces\/qiubilong\/028.gif","qbl029":"faces\/qiubilong\/029.gif","qbl030":"faces\/qiubilong\/030.gif","qbl031":"faces\/qiubilong\/031.gif","qbl032":"faces\/qiubilong\/032.gif","qbl033":"faces\/qiubilong\/033.gif","qbl034":"faces\/qiubilong\/034.gif","qbl035":"faces\/qiubilong\/035.gif","qbl036":"faces\/qiubilong\/036.gif","qbl037":"faces\/qiubilong\/037.gif","qbl038":"faces\/qiubilong\/038.gif","qbl039":"faces\/qiubilong\/039.gif","qbl040":"faces\/qiubilong\/040.gif","qbl041":"faces\/qiubilong\/041.gif","qbl042":"faces\/qiubilong\/042.gif","qbl043":"faces\/qiubilong\/043.gif","qbl044":"faces\/qiubilong\/044.gif","qbl045":"faces\/qiubilong\/045.gif","qbl046":"faces\/qiubilong\/046.gif","qbl047":"faces\/qiubilong\/047.gif","qbl048":"faces\/qiubilong\/048.gif","qbl049":"faces\/qiubilong\/049.gif","qbl050":"faces\/qiubilong\/050.gif","qbl051":"faces\/qiubilong\/051.gif","qbl052":"faces\/qiubilong\/052.gif","qbl053":"faces\/qiubilong\/053.gif","qbl054":"faces\/qiubilong\/054.gif","qbl055":"faces\/qiubilong\/055.gif","qbl056":"faces\/qiubilong\/056.gif","qbl057":"faces\/qiubilong\/057.gif","qbl058":"faces\/qiubilong\/058.gif","qbl059":"faces\/qiubilong\/059.gif","qbl060":"faces\/qiubilong\/060.gif","qbl061":"faces\/qiubilong\/061.gif","qbl062":"faces\/qiubilong\/062.gif","qbl063":"faces\/qiubilong\/063.gif","qbl064":"faces\/qiubilong\/064.gif","qbl065":"faces\/qiubilong\/065.gif","qbl066":"faces\/qiubilong\/066.gif","qbl067":"faces\/qiubilong\/067.gif","qbl068":"faces\/qiubilong\/068.gif","qbl069":"faces\/qiubilong\/069.gif","qbl070":"faces\/qiubilong\/070.gif","qbl071":"faces\/qiubilong\/071.gif","qbl072":"faces\/qiubilong\/072.gif","qbl073":"faces\/qiubilong\/073.gif","qbl074":"faces\/qiubilong\/074.gif","qbl075":"faces\/qiubilong\/075.gif","qbl076":"faces\/qiubilong\/076.gif","qbl077":"faces\/qiubilong\/077.gif","qbl078":"faces\/qiubilong\/078.gif","qbl079":"faces\/qiubilong\/079.gif","qbl080":"faces\/qiubilong\/080.gif","qbl081":"faces\/qiubilong\/081.gif","qbl082":"faces\/qiubilong\/082.gif","qbl083":"faces\/qiubilong\/083.gif","qbl084":"faces\/qiubilong\/084.gif","ppb0":"faces\/ppb\/0.gif","ppb1":"faces\/ppb\/1.gif","ppb2":"faces\/ppb\/2.gif","ppb3":"faces\/ppb\/3.gif","ppb4":"faces\/ppb\/4.gif","ppb5":"faces\/ppb\/5.gif","ppb6":"faces\/ppb\/6.gif","ppb7":"faces\/ppb\/7.gif","ppb8":"faces\/ppb\/8.gif","ppb9":"faces\/ppb\/9.gif","ppb10":"faces\/ppb\/10.gif","ppb11":"faces\/ppb\/11.gif","ppb12":"faces\/ppb\/12.gif","ppb13":"faces\/ppb\/13.gif","ppb14":"faces\/ppb\/14.gif","ppb15":"faces\/ppb\/15.gif","ppb16":"faces\/ppb\/16.gif","ppb17":"faces\/ppb\/17.gif","ppb18":"faces\/ppb\/18.gif","ppb19":"faces\/ppb\/19.gif","ppb20":"faces\/ppb\/20.gif","ppb21":"faces\/ppb\/21.gif","ppb22":"faces\/ppb\/22.gif","ppb23":"faces\/ppb\/23.gif","ppb24":"faces\/ppb\/24.gif","ppb25":"faces\/ppb\/25.gif","ppb26":"faces\/ppb\/26.gif","ppb27":"faces\/ppb\/27.gif"},"group":{"2":{"id":2,"name":"\u9ed8\u8ba4","faces":[{"id":"55","title":"00","txt":"QQ000","url":"faces\/qq_default\/MRF_100000.gif","group_id":"2","created_time":"2017-03-20 11:07:03"},{"id":"56","title":"01","txt":"QQ001","url":"faces\/qq_default\/MRF_100001.gif","group_id":"2","created_time":"2017-03-20 11:07:03"},{"id":"57","title":"02","txt":"QQ002","url":"faces\/qq_default\/MRF_100002.gif","group_id":"2","created_time":"2017-03-20 11:07:03"},{"id":"58","title":"03","txt":"QQ003","url":"faces\/qq_default\/MRF_100003.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"59","title":"04","txt":"QQ004","url":"faces\/qq_default\/MRF_100004.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"60","title":"05","txt":"QQ005","url":"faces\/qq_default\/MRF_100005.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"61","title":"06","txt":"QQ006","url":"faces\/qq_default\/MRF_100006.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"62","title":"07","txt":"QQ007","url":"faces\/qq_default\/MRF_100007.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"63","title":"08","txt":"QQ008","url":"faces\/qq_default\/MRF_100008.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"64","title":"09","txt":"QQ009","url":"faces\/qq_default\/MRF_100009.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"65","title":"10","txt":"QQ010","url":"faces\/qq_default\/MRF_100010.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"66","title":"11","txt":"QQ011","url":"faces\/qq_default\/MRF_100011.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"67","title":"12","txt":"QQ012","url":"faces\/qq_default\/MRF_100012.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"68","title":"13","txt":"QQ013","url":"faces\/qq_default\/MRF_100013.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"69","title":"14","txt":"QQ014","url":"faces\/qq_default\/MRF_100014.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"70","title":"15","txt":"QQ015","url":"faces\/qq_default\/MRF_100015.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"71","title":"16","txt":"QQ016","url":"faces\/qq_default\/MRF_100016.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"72","title":"17","txt":"QQ017","url":"faces\/qq_default\/MRF_100017.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"73","title":"18","txt":"QQ018","url":"faces\/qq_default\/MRF_100018.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"74","title":"19","txt":"QQ019","url":"faces\/qq_default\/MRF_100019.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"75","title":"20","txt":"QQ020","url":"faces\/qq_default\/MRF_100020.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"76","title":"21","txt":"QQ021","url":"faces\/qq_default\/MRF_100021.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"77","title":"22","txt":"QQ022","url":"faces\/qq_default\/MRF_100022.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"78","title":"23","txt":"QQ023","url":"faces\/qq_default\/MRF_100023.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"79","title":"24","txt":"QQ024","url":"faces\/qq_default\/MRF_100024.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"80","title":"25","txt":"QQ025","url":"faces\/qq_default\/MRF_100025.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"81","title":"26","txt":"QQ026","url":"faces\/qq_default\/MRF_100026.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"82","title":"27","txt":"QQ027","url":"faces\/qq_default\/MRF_100027.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"83","title":"28","txt":"QQ028","url":"faces\/qq_default\/MRF_100028.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"84","title":"29","txt":"QQ029","url":"faces\/qq_default\/MRF_100029.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"85","title":"30","txt":"QQ030","url":"faces\/qq_default\/MRF_100030.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"86","title":"31","txt":"QQ031","url":"faces\/qq_default\/MRF_100031.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"87","title":"32","txt":"QQ032","url":"faces\/qq_default\/MRF_100032.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"88","title":"33","txt":"QQ033","url":"faces\/qq_default\/MRF_100033.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"89","title":"34","txt":"QQ034","url":"faces\/qq_default\/MRF_100034.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"90","title":"35","txt":"QQ035","url":"faces\/qq_default\/MRF_100035.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"91","title":"36","txt":"QQ036","url":"faces\/qq_default\/MRF_100036.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"92","title":"37","txt":"QQ037","url":"faces\/qq_default\/MRF_100037.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"93","title":"38","txt":"QQ038","url":"faces\/qq_default\/MRF_100038.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"94","title":"39","txt":"QQ039","url":"faces\/qq_default\/MRF_100039.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"95","title":"40","txt":"QQ040","url":"faces\/qq_default\/MRF_100040.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"96","title":"41","txt":"QQ041","url":"faces\/qq_default\/MRF_100041.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"97","title":"42","txt":"QQ042","url":"faces\/qq_default\/MRF_100042.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"98","title":"43","txt":"QQ043","url":"faces\/qq_default\/MRF_100043.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"99","title":"44","txt":"QQ044","url":"faces\/qq_default\/MRF_100044.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"100","title":"45","txt":"QQ045","url":"faces\/qq_default\/MRF_100045.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"101","title":"46","txt":"QQ046","url":"faces\/qq_default\/MRF_100046.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"102","title":"47","txt":"QQ047","url":"faces\/qq_default\/MRF_100047.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"103","title":"48","txt":"QQ048","url":"faces\/qq_default\/MRF_100048.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"104","title":"49","txt":"QQ049","url":"faces\/qq_default\/MRF_100049.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"105","title":"50","txt":"QQ050","url":"faces\/qq_default\/MRF_100050.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"106","title":"51","txt":"QQ051","url":"faces\/qq_default\/MRF_100051.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"107","title":"52","txt":"QQ052","url":"faces\/qq_default\/MRF_100052.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"108","title":"53","txt":"QQ053","url":"faces\/qq_default\/MRF_100053.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"109","title":"54","txt":"QQ054","url":"faces\/qq_default\/MRF_100054.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"110","title":"55","txt":"QQ055","url":"faces\/qq_default\/MRF_100055.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"111","title":"56","txt":"QQ056","url":"faces\/qq_default\/MRF_100056.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"112","title":"57","txt":"QQ057","url":"faces\/qq_default\/MRF_100057.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"113","title":"58","txt":"QQ058","url":"faces\/qq_default\/MRF_100058.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"114","title":"59","txt":"QQ059","url":"faces\/qq_default\/MRF_100059.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"115","title":"60","txt":"QQ060","url":"faces\/qq_default\/MRF_100060.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"116","title":"61","txt":"QQ061","url":"faces\/qq_default\/MRF_100061.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"117","title":"62","txt":"QQ062","url":"faces\/qq_default\/MRF_100062.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"118","title":"63","txt":"QQ063","url":"faces\/qq_default\/MRF_100063.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"119","title":"64","txt":"QQ064","url":"faces\/qq_default\/MRF_100064.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"120","title":"65","txt":"QQ065","url":"faces\/qq_default\/MRF_100065.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"121","title":"66","txt":"QQ066","url":"faces\/qq_default\/MRF_100066.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"122","title":"67","txt":"QQ067","url":"faces\/qq_default\/MRF_100067.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"123","title":"68","txt":"QQ068","url":"faces\/qq_default\/MRF_100068.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"124","title":"69","txt":"QQ069","url":"faces\/qq_default\/MRF_100069.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"125","title":"70","txt":"QQ070","url":"faces\/qq_default\/MRF_100070.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"126","title":"71","txt":"QQ071","url":"faces\/qq_default\/MRF_100071.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"127","title":"72","txt":"QQ072","url":"faces\/qq_default\/MRF_100072.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"128","title":"73","txt":"QQ073","url":"faces\/qq_default\/MRF_100073.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"129","title":"74","txt":"QQ074","url":"faces\/qq_default\/MRF_100074.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"130","title":"75","txt":"QQ075","url":"faces\/qq_default\/MRF_100075.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"131","title":"76","txt":"QQ076","url":"faces\/qq_default\/MRF_100076.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"132","title":"77","txt":"QQ077","url":"faces\/qq_default\/MRF_100077.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"133","title":"78","txt":"QQ078","url":"faces\/qq_default\/MRF_100078.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"134","title":"79","txt":"QQ079","url":"faces\/qq_default\/MRF_100079.gif","group_id":"2","created_time":"2017-03-20 11:07:04"},{"id":"135","title":"80","txt":"QQ080","url":"faces\/qq_default\/MRF_100080.gif","group_id":"2","created_time":"2017-03-20 11:07:04"}]},"3":{"id":3,"name":"\u5154\u65af\u57fa","faces":[{"id":"136","title":"01","txt":"tuzki_01","url":"faces\/tuzki\/01.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"137","title":"02","txt":"tuzki_02","url":"faces\/tuzki\/02.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"138","title":"03","txt":"tuzki_03","url":"faces\/tuzki\/03.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"139","title":"04","txt":"tuzki_04","url":"faces\/tuzki\/04.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"140","title":"05","txt":"tuzki_05","url":"faces\/tuzki\/05.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"141","title":"06","txt":"tuzki_06","url":"faces\/tuzki\/06.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"142","title":"07","txt":"tuzki_07","url":"faces\/tuzki\/07.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"143","title":"08","txt":"tuzki_08","url":"faces\/tuzki\/08.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"144","title":"09","txt":"tuzki_09","url":"faces\/tuzki\/09.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"145","title":"10","txt":"tuzki_10","url":"faces\/tuzki\/10.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"146","title":"11","txt":"tuzki_11","url":"faces\/tuzki\/11.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"147","title":"12","txt":"tuzki_12","url":"faces\/tuzki\/12.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"148","title":"13","txt":"tuzki_13","url":"faces\/tuzki\/13.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"149","title":"14","txt":"tuzki_14","url":"faces\/tuzki\/14.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"150","title":"15","txt":"tuzki_15","url":"faces\/tuzki\/15.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"151","title":"16","txt":"tuzki_16","url":"faces\/tuzki\/16.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"152","title":"17","txt":"tuzki_17","url":"faces\/tuzki\/17.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"153","title":"18","txt":"tuzki_18","url":"faces\/tuzki\/18.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"154","title":"19","txt":"tuzki_19","url":"faces\/tuzki\/19.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"155","title":"20","txt":"tuzki_20","url":"faces\/tuzki\/20.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"156","title":"21","txt":"tuzki_21","url":"faces\/tuzki\/21.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"157","title":"22","txt":"tuzki_22","url":"faces\/tuzki\/22.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"158","title":"23","txt":"tuzki_23","url":"faces\/tuzki\/23.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"159","title":"24","txt":"tuzki_24","url":"faces\/tuzki\/24.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"160","title":"25","txt":"tuzki_25","url":"faces\/tuzki\/25.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"161","title":"26","txt":"tuzki_26","url":"faces\/tuzki\/26.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"162","title":"27","txt":"tuzki_27","url":"faces\/tuzki\/27.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"163","title":"28","txt":"tuzki_28","url":"faces\/tuzki\/28.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"164","title":"29","txt":"tuzki_29","url":"faces\/tuzki\/29.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"165","title":"30","txt":"tuzki_30","url":"faces\/tuzki\/30.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"166","title":"31","txt":"tuzki_31","url":"faces\/tuzki\/31.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"167","title":"32","txt":"tuzki_32","url":"faces\/tuzki\/32.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"168","title":"33","txt":"tuzki_33","url":"faces\/tuzki\/33.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"169","title":"34","txt":"tuzki_34","url":"faces\/tuzki\/34.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"170","title":"35","txt":"tuzki_35","url":"faces\/tuzki\/35.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"171","title":"36","txt":"tuzki_36","url":"faces\/tuzki\/36.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"172","title":"37","txt":"tuzki_37","url":"faces\/tuzki\/37.gif","group_id":"3","created_time":"2016-10-04 13:37:27"},{"id":"173","title":"38","txt":"tuzki_38","url":"faces\/tuzki\/38.gif","group_id":"3","created_time":"2016-10-04 13:37:27"}]},"4":{"id":4,"name":"\u4e18\u6bd4\u9f99","faces":[{"id":"174","title":"001","txt":"qbl001","url":"faces\/qiubilong\/001.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"175","title":"002","txt":"qbl002","url":"faces\/qiubilong\/002.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"176","title":"003","txt":"qbl003","url":"faces\/qiubilong\/003.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"177","title":"004","txt":"qbl004","url":"faces\/qiubilong\/004.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"178","title":"005","txt":"qbl005","url":"faces\/qiubilong\/005.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"179","title":"006","txt":"qbl006","url":"faces\/qiubilong\/006.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"180","title":"007","txt":"qbl007","url":"faces\/qiubilong\/007.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"181","title":"008","txt":"qbl008","url":"faces\/qiubilong\/008.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"182","title":"009","txt":"qbl009","url":"faces\/qiubilong\/009.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"183","title":"010","txt":"qbl010","url":"faces\/qiubilong\/010.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"184","title":"011","txt":"qbl011","url":"faces\/qiubilong\/011.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"185","title":"012","txt":"qbl012","url":"faces\/qiubilong\/012.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"186","title":"013","txt":"qbl013","url":"faces\/qiubilong\/013.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"187","title":"014","txt":"qbl014","url":"faces\/qiubilong\/014.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"188","title":"015","txt":"qbl015","url":"faces\/qiubilong\/015.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"189","title":"016","txt":"qbl016","url":"faces\/qiubilong\/016.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"190","title":"017","txt":"qbl017","url":"faces\/qiubilong\/017.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"191","title":"018","txt":"qbl018","url":"faces\/qiubilong\/018.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"192","title":"019","txt":"qbl019","url":"faces\/qiubilong\/019.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"193","title":"020","txt":"qbl020","url":"faces\/qiubilong\/020.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"194","title":"021","txt":"qbl021","url":"faces\/qiubilong\/021.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"195","title":"022","txt":"qbl022","url":"faces\/qiubilong\/022.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"196","title":"023","txt":"qbl023","url":"faces\/qiubilong\/023.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"197","title":"024","txt":"qbl024","url":"faces\/qiubilong\/024.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"198","title":"025","txt":"qbl025","url":"faces\/qiubilong\/025.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"199","title":"026","txt":"qbl026","url":"faces\/qiubilong\/026.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"200","title":"027","txt":"qbl027","url":"faces\/qiubilong\/027.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"201","title":"028","txt":"qbl028","url":"faces\/qiubilong\/028.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"202","title":"029","txt":"qbl029","url":"faces\/qiubilong\/029.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"203","title":"030","txt":"qbl030","url":"faces\/qiubilong\/030.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"204","title":"031","txt":"qbl031","url":"faces\/qiubilong\/031.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"205","title":"032","txt":"qbl032","url":"faces\/qiubilong\/032.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"206","title":"033","txt":"qbl033","url":"faces\/qiubilong\/033.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"207","title":"034","txt":"qbl034","url":"faces\/qiubilong\/034.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"208","title":"035","txt":"qbl035","url":"faces\/qiubilong\/035.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"209","title":"036","txt":"qbl036","url":"faces\/qiubilong\/036.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"210","title":"037","txt":"qbl037","url":"faces\/qiubilong\/037.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"211","title":"038","txt":"qbl038","url":"faces\/qiubilong\/038.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"212","title":"039","txt":"qbl039","url":"faces\/qiubilong\/039.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"213","title":"040","txt":"qbl040","url":"faces\/qiubilong\/040.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"214","title":"041","txt":"qbl041","url":"faces\/qiubilong\/041.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"215","title":"042","txt":"qbl042","url":"faces\/qiubilong\/042.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"216","title":"043","txt":"qbl043","url":"faces\/qiubilong\/043.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"217","title":"044","txt":"qbl044","url":"faces\/qiubilong\/044.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"218","title":"045","txt":"qbl045","url":"faces\/qiubilong\/045.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"219","title":"046","txt":"qbl046","url":"faces\/qiubilong\/046.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"220","title":"047","txt":"qbl047","url":"faces\/qiubilong\/047.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"221","title":"048","txt":"qbl048","url":"faces\/qiubilong\/048.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"222","title":"049","txt":"qbl049","url":"faces\/qiubilong\/049.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"223","title":"050","txt":"qbl050","url":"faces\/qiubilong\/050.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"224","title":"051","txt":"qbl051","url":"faces\/qiubilong\/051.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"225","title":"052","txt":"qbl052","url":"faces\/qiubilong\/052.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"226","title":"053","txt":"qbl053","url":"faces\/qiubilong\/053.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"227","title":"054","txt":"qbl054","url":"faces\/qiubilong\/054.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"228","title":"055","txt":"qbl055","url":"faces\/qiubilong\/055.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"229","title":"056","txt":"qbl056","url":"faces\/qiubilong\/056.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"230","title":"057","txt":"qbl057","url":"faces\/qiubilong\/057.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"231","title":"058","txt":"qbl058","url":"faces\/qiubilong\/058.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"232","title":"059","txt":"qbl059","url":"faces\/qiubilong\/059.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"233","title":"060","txt":"qbl060","url":"faces\/qiubilong\/060.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"234","title":"061","txt":"qbl061","url":"faces\/qiubilong\/061.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"235","title":"062","txt":"qbl062","url":"faces\/qiubilong\/062.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"236","title":"063","txt":"qbl063","url":"faces\/qiubilong\/063.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"237","title":"064","txt":"qbl064","url":"faces\/qiubilong\/064.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"238","title":"065","txt":"qbl065","url":"faces\/qiubilong\/065.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"239","title":"066","txt":"qbl066","url":"faces\/qiubilong\/066.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"240","title":"067","txt":"qbl067","url":"faces\/qiubilong\/067.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"241","title":"068","txt":"qbl068","url":"faces\/qiubilong\/068.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"242","title":"069","txt":"qbl069","url":"faces\/qiubilong\/069.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"243","title":"070","txt":"qbl070","url":"faces\/qiubilong\/070.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"244","title":"071","txt":"qbl071","url":"faces\/qiubilong\/071.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"245","title":"072","txt":"qbl072","url":"faces\/qiubilong\/072.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"246","title":"073","txt":"qbl073","url":"faces\/qiubilong\/073.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"247","title":"074","txt":"qbl074","url":"faces\/qiubilong\/074.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"248","title":"075","txt":"qbl075","url":"faces\/qiubilong\/075.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"249","title":"076","txt":"qbl076","url":"faces\/qiubilong\/076.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"250","title":"077","txt":"qbl077","url":"faces\/qiubilong\/077.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"251","title":"078","txt":"qbl078","url":"faces\/qiubilong\/078.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"252","title":"079","txt":"qbl079","url":"faces\/qiubilong\/079.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"253","title":"080","txt":"qbl080","url":"faces\/qiubilong\/080.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"254","title":"081","txt":"qbl081","url":"faces\/qiubilong\/081.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"255","title":"082","txt":"qbl082","url":"faces\/qiubilong\/082.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"256","title":"083","txt":"qbl083","url":"faces\/qiubilong\/083.gif","group_id":"4","created_time":"2017-03-20 11:07:04"},{"id":"257","title":"084","txt":"qbl084","url":"faces\/qiubilong\/084.gif","group_id":"4","created_time":"2017-03-20 11:07:04"}]},"5":{"id":5,"name":"\u70ae\u70ae\u5175","faces":[{"id":"258","title":"0","txt":"ppb0","url":"faces\/ppb\/0.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"259","title":"1","txt":"ppb1","url":"faces\/ppb\/1.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"260","title":"2","txt":"ppb2","url":"faces\/ppb\/2.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"261","title":"3","txt":"ppb3","url":"faces\/ppb\/3.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"262","title":"4","txt":"ppb4","url":"faces\/ppb\/4.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"263","title":"5","txt":"ppb5","url":"faces\/ppb\/5.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"264","title":"6","txt":"ppb6","url":"faces\/ppb\/6.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"265","title":"7","txt":"ppb7","url":"faces\/ppb\/7.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"266","title":"8","txt":"ppb8","url":"faces\/ppb\/8.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"267","title":"9","txt":"ppb9","url":"faces\/ppb\/9.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"268","title":"10","txt":"ppb10","url":"faces\/ppb\/10.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"269","title":"11","txt":"ppb11","url":"faces\/ppb\/11.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"270","title":"12","txt":"ppb12","url":"faces\/ppb\/12.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"271","title":"13","txt":"ppb13","url":"faces\/ppb\/13.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"272","title":"14","txt":"ppb14","url":"faces\/ppb\/14.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"273","title":"15","txt":"ppb15","url":"faces\/ppb\/15.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"274","title":"16","txt":"ppb16","url":"faces\/ppb\/16.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"275","title":"17","txt":"ppb17","url":"faces\/ppb\/17.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"276","title":"18","txt":"ppb18","url":"faces\/ppb\/18.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"277","title":"19","txt":"ppb19","url":"faces\/ppb\/19.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"278","title":"20","txt":"ppb20","url":"faces\/ppb\/20.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"279","title":"21","txt":"ppb21","url":"faces\/ppb\/21.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"280","title":"22","txt":"ppb22","url":"faces\/ppb\/22.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"281","title":"23","txt":"ppb23","url":"faces\/ppb\/23.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"282","title":"24","txt":"ppb24","url":"faces\/ppb\/24.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"283","title":"25","txt":"ppb25","url":"faces\/ppb\/25.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"284","title":"26","txt":"ppb26","url":"faces\/ppb\/26.gif","group_id":"5","created_time":"2017-03-20 11:07:04"},{"id":"285","title":"27","txt":"ppb27","url":"faces\/ppb\/27.gif","group_id":"5","created_time":"2017-03-20 11:07:04"}]}}};
const _face_prefix = '/images/';
let pager = {
    /**
     *
     * @param _data 可能是指定用户的UserID和名称，发起一个邀请
     *  可能是一个邀请数据结构，用于接受一个邀请。
     */
    invite:function(_data){
        debug_log(_data);
        if(!userinfo)
        {
            layer.alert(pager.t("Please Login"));
            return false;
        }
        let invite_form = $("#invite_form");
        let invite_btn = $("#invite_submit_button");
        invite_btn.removeAttr("disabled");
        if(typeof _data.user_id != "undefined")//click on someone's invite button
        {
            invite_form.find("input[name=to_user]").val(_data.user_id);
            invite_form.find("input[name=id]").val(0);
            invite_form.find("input[name=use_black]").eq(0).prop("checked",true);
            invite_form.find(".opponent_name").text(_data.nickname);
            invite_form.find("input[name=hours]").val(0);
            invite_form.find("input[name=minutes]").val(10);
            invite_form.find("select[name=rule]").val("RIF");
            invite_form.find("input[name=comment]").val("");
            invite_form.find("input[name=step_add_sec]").val("0");
            invite_form.find("input[name=free_open]").prop("checked",false);
            invite_form.find("input[name=allow_undo]").prop("checked",false);
            invite_form.find("input[name=is_private]").prop("checked",false);
            invite_form.find("input[name=allow_ob_talk]").prop("checked",false);
            invite_btn.val(pager.t("Send Invite"));
        }
        else// 被人邀请，弹出被邀请的窗口
        {
            invite_form.find("input[name=to_user]").val(0);
            invite_form.find("input[name=id]").val(_data.id);
            //use black
            if(_data.black_id == _data.to)
            {
                invite_form.find("input[name=use_black]").eq(0).prop("checked",true);
                invite_form.find("input[name=use_black]").eq(1).prop("checked",false);
            }
            else
            {
                invite_form.find("input[name=use_black]").eq(0).prop("checked",false);
                invite_form.find("input[name=use_black]").eq(1).prop("checked",true);
            }
            invite_form.find(".opponent_name").text(_data.from_user.nickname);
            invite_form.find("input[name=hours]").val(parseInt(_data.totaltime / 3600));
            invite_form.find("input[name=minutes]").val(parseInt(_data.totaltime / 60) % 60);
            invite_form.find("select[name=rule]").val(_data.rule);
            invite_form.find("input[name=comment]").val(_data.message);
            invite_form.find("input[name=step_add_sec]").val(_data.step_add_sec);
            invite_form.find("input[name=free_open]").prop("checked",(_data.free_opening == "1"));
            invite_form.find("input[name=allow_undo]").prop("checked",(_data.allow_undo == "1"));
            invite_form.find("input[name=is_private]").prop("checked",(_data.is_private == "1"));
            invite_form.find("input[name=allow_ob_talk]").prop("checked",(_data.allow_ob_talk == "1"));
            invite_btn.val(pager.t("Accept Invite"));
        }
        layer.open({
            type:1,
            content:$("#invite_box"),
            title:pager.t("Invite"),
            shade: 0,
            cancel: function(index, layero){
                $("#invite_box").hide();
                layer.close(index);
            }
        });
    },
    ask_for_a5:function(){
        layer.prompt({
            formType: 0,
            value: '2',
            title: pager.t("How many 5th would you offer")
        }, function(value, index, elem){
            $.post("/games/play/a5_number",{
                number:value,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
                game_id:gameObj.id
            },function(_data){
                if(_data.code == 200)
                {
                    layer.close(index);
                }
                else
                {
                    layer.alert(_data.msg);
                    board.show_origin();
                }
            },"json");
        });
    },
    show_msg: function(content,user,board_str){
        const chat_container = $("#chat_content");
        if(chat_container.find("li").length > 150)
        {
            chat_container.empty();
        }
        let new_li = $(document.createElement("li"));
        /** 处理表情**/
        let match,matched={};
        let face_reg = new RegExp(/\[(\w+)\]/g);
        //const _prefix = '/images/';
        try{
            while(match = face_reg.exec(content))
            {
                if(typeof faces.map[match[1]] == 'string')
                {
                    matched[match[1]] = 1;
                }
            }
            for(let sub in matched){
                content = content.split('['+sub+']').join('<img src="'+_face_prefix+faces.map[sub]+'" />');
            }
        }catch(e){
            console.log(e);
        }
        //表情结束
        if(typeof user == "object")
        {
            new_li.append("<span class='user_nickname'>" + user.nickname +  "</span> " + pager.t("Says: "));
        }
        new_li.append("<span class='chat_content'>" + content + "</span>").appendTo(chat_container);
        //+board
        if(typeof board_str == 'string' && board_str != '')
        {
            $("<a href='javascript:void(0);'>[" + pager.t("My Analyze") + "]</a>").click(function(){board.show_analyze(board_str)}).appendTo(new_li);
        }
        //滚动。
        $("#chat_content_list").scrollTop($("#chat_content_list")[0].scrollHeight - $("#chat_content_list").height());
    },
    show_game_list: function(games){
        $("#hall_games>ul").find("li:not(:first)").remove();
        for(let i in games)
        {
            let new_li = $(document.createElement("li"));
            $(document.createElement('span')).addClass("layui-col-xs2").text(games[i].id + ( games[i].is_private == '1' ? ' (p)' : '' )).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").text(games[i].black.nickname).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").text(games[i].white.nickname).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").text(games[i].game_record.length/2).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").text(result_defines[games[i].status]).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").html("<a href='/game/"+games[i].id+"'>" + pager.t("Enter") + "</a>").appendTo(new_li);
            new_li.appendTo($("#hall_games>ul"));
        }
    },
    play_sound : (function(){
        const _audio = $("#global-audio");
        const sounds = {
            'GameOver' : "/sound/GameOver.wav",
            'Invitation' : "/sound/Invitation.wav",
            'Move' : "/sound/Move.wav",
            'Back' : "/sound/Back.wav"
        };
        return function(_name){
            if(typeof sounds[_name] == "string")
            {
                _audio[0].pause();
                if(sounds[_name] != _audio.attr('src'))
                {
                    _audio.attr('src',sounds[_name]);
                }
                _audio[0].play();
            }
        }
    })(),
    show_user_list : function(client_list){
        $("#chat_user_list>ul").find("li:not(:first)").remove();
        for(let i in client_list)
        {
            let user = (typeof client_list[i].user == "object") ? client_list[i].user : null;
            let new_li = $(document.createElement("li"));
            let name_span = $(document.createElement('span'));
            let score_span = $(document.createElement('span'));
            if(user)
            {
                name_span.attr({alt:user.intro,"data-uid":user.id})
                    .click(function(){
                        if(userinfo && userinfo.id != $(this).attr("data-uid"))
                        {
                            pager.invite({user_id:$(this).attr("data-uid"),nickname:$(this).text()});
                        }
                        else if(userinfo && userinfo.id == $(this).attr("data-uid"))
                        {
                            layer.prompt({
                                formType: 0,
                                value: $(this).attr("alt"),
                                title: pager.t("Edit Personal Intro"),
                            }, function(value, index, elem){
                                $.post(
                                    "/user/edit",
                                    {
                                        intro:value,
                                        "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
                                        game_id: typeof gameObj == "undefined" ? "HALL" : gameObj.id
                                    },
                                    function(_data){
                                        if(_data.code != 200)
                                        {
                                            layer.alert(_data.msg);
                                        }
                                    },
                                    "json"
                                );
                                layer.close(index);
                            });
                        }
                    })
                    .mouseover(function(){
                        if($(this).attr("alt"))
                        {
                            layer.tips($(this).attr("alt"),this,{tips:4,time:1500});
                        }
                    })
                    .text(user.nickname);
                score_span.html($("<a>").attr({href:'/games/history/'+user.id,target:'_blank'}).text(user.score));
            }
            else
            {
                name_span.text(pager.t("guest"));
                score_span.text('0');
            }
            name_span.addClass("layui-col-xs7 name_tag").appendTo(new_li);
            score_span.addClass("layui-col-xs5").appendTo(new_li);
            new_li.appendTo($("#chat_user_list>ul"));
        }
    },
    send_chat : function(){
        let content = $("#msg").val().trim();
        if(!content)
        {
            layer.tips(pager.t("Don't send empty content"),$("#chat_operate_area .send"),{tips:1,time:1000});
            return false;
        }
        $.post(
            "/games/chat/say",
            {
                content:content,
                board:$("#chat_operate_area .icon-board").attr('data-board'),
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
                game_id: typeof gameObj == "undefined" ? "HALL" : gameObj.id
            },
            function(_data){
                if(_data.code != 200)
                {
                    layer.alert(_data.msg);
                }
                else
                {
                    $("#msg").val("");
                    $("#chat_operate_area .icon-board").attr("data-board","").removeClass("checked");
                }
            },
            "json"
        );
    },

    show_undo : function(undo_data){
        layer.confirm(pager.t("Your opponent wants to Undo to ") + undo_data.to_step + pager.t("th move"),
            {
                icon: 3,
                title:pager.t("Undo Apply"),
                btn:[pager.t("Accept"),pager.t('Reject')]
            },
            function(index){
                $.post('/games/undo/reply',{
                    undo_id:undo_data.id,
                    action:'accept',
                    "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
                },function(data){
                    if(data.code != 200)
                    {
                        layer.alert(data.msg);
                    }
                },"json");
                layer.close(index);
            },
            function(index){
                $.post('/games/undo/reply',{
                    undo_id:undo_data.id,
                    action:'reject',
                    "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
                },function(data){
                    if(data.code != 200)
                    {
                        layer.alert(data.msg);
                    }
                },"json");
                layer.close(index);
            }
        );
    },
    switch_language : function(language){
        $.post("/site/switch_language",{
            language:language,
            "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
        },function(_data){
            if(_data.code != 200)
            {
                layer.alert(_data.msg);
            }
            else
            {
                window.location.reload();
            }
        },"json");
    },
    t : function(message){
        return (typeof lang_map[message] == 'string') ? lang_map[message] : message;
    }
};

$(document).ready(function () {
    //聊天
    $("#msg").keydown(function(event){
        if(event.keyCode == 13)
        {
            pager.send_chat();
        }
    });
    $("#chat_operate_area .send").click(pager.send_chat);
    $("#chat_operate_area .icon-emoji").click(function(){
        let _pos = $("#chat_operate_area .icon-emoji").position();
        $("#face_pop").css({top:_pos.top - 288,left:0,opacity:1}).toggle();
        face_render(faces.group);
    });
    $("#chat_operate_area .icon-board").click(function(){
        if($(this).hasClass('checked'))
        {
            $(this).attr("data-board","").removeClass("checked");
        }
        else
        {
            if(board.switch_mode('analyze',false))
            {
                $(this).attr("data-board",board.get_current_board()).addClass("checked");
            }
        }
    });

    //表情
    let face_init = false;
    let face_render = function(data){
        if(data==null || face_init)return;
        let html = "",k = 0;
        let link_html = $('<div class="tab">');
        for(let i in data) {
            let _each = data[i];
            if(k==0)
            {
                html+='<div class="face-default" style="display: block;">';
                $('<a class="default active" data-index="' +k+ '" title="'+_each.name+'"><span>'+_each.name+'</span></a>').appendTo(link_html);
            }
            else
            {
                html+='<div class="face-default">';
                $('<a class="default" data-index="' +k+ '" title="'+_each.name+'"><span>'+_each.name+'</span></a>').appendTo(link_html);
            }
            if (_each.faces != null && _each.faces.length > 0) {
                for (let j=0;j<_each.faces.length;j++) {
                    html += '<a class="can-choose" data-id="'+_each.faces[j].txt+'" data-src="'+_face_prefix+_each.faces[j].url+'"><img title="'+_each.faces[j].title+'" alt="'+_each.faces[j].title+'" src="'+_face_prefix+_each.faces[j].url+'"></a>'
                }
            }
            html += '</div>';
            k++;
        }
        $("#face_pop .face-content").html(html);
        $("#face_pop .tab").html(link_html);

        //绑定事件
        link_html.find("a").click(function(){
            let clicked = $(this);
            clicked.addClass("active").siblings().removeClass("active");
            $(".face-content .face-default").eq(clicked.attr('data-index')).show().siblings().hide();
        });
        $(".face-content .can-choose").click(function(){
            let _id = $(this).attr("data-id");
            $("#msg").val($("#msg").val() + '[' + _id + '] ').focus();
            $("#face_pop").hide();
        });
        //事件end
        face_init = true;
    };

    //邀请游戏
    $("#invite_submit_button").click(function () {
        $("#invite_submit_button").attr("disabled","disabled");
        $.post("/games/invite/create",$("#invite_form").serialize(),function(_return){
            $("#invite_submit_button").removeAttr("disabled");
            layer.closeAll();
            layer.alert(_return.msg);
            $("#invite_box").hide();
        },"json");
    });

    //交换
    $("#swap_button").click(function(){
        $.post('/games/play/swap',{
            game_id:gameObj.id,
            "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
        },function(data){
            if(data.code != 200)
            {
                layer.alert(data.msg);
            }
        },"json");
    });

    //塔10
    $("#tara_option1_button").click(function(){
        $.post('/games/play/tara-option1',{
            game_id:gameObj.id,
            "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
        },function(data){
            if(data.code != 200)
            {
                layer.alert(data.msg);
            }
        },"json");
    });

    //提和
    $("#draw_button").click(function(){
        layer.confirm(pager.t('You want to offer a draw?'),{icon:3,title:pager.t("Offer draw")},function(index){
            $.post('/games/play/offer_draw',{
                game_id:gameObj.id,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
            },function(data){
                if(data.code != 200)
                {
                    layer.alert(data.msg);
                }
            },"json");
            layer.close(index);
        });
    });

    //认输
    $("#resign_button").click(function(){
        layer.confirm(pager.t('You sure you want resign?'),{icon:5,title:pager.t('Resign')},function(index){
            $.post('/games/play/resign',{
                game_id:gameObj.id,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
            },function(data){
                if(data.code != 200)
                {
                    layer.alert(data.msg);
                }
            },"json");
            layer.close(index);
        });
    });

    //悔棋
    $("#undo_button").click(function(){
        layer.prompt({
            formType: 0,
            value: board.is_my_turn ? ((board.get_current_board().length / 2) - 1) : (board.get_current_board().length / 2),
            title: pager.t('Where do you want to Undo to?')
        }, function(value, index, elem){
            $.post("/games/undo/create",{
                to_step:value,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
                game_id:gameObj.id
            },function(_data){
                if(_data.code == 200)
                {
                    layer.close(index);
                }
                else
                {
                    layer.alert(_data.msg);
                }
            },"json");
        });
    });

    //悔棋记录的展示
    $(".undo_records select").change(function(){
        if($(this).val())
        {
            board.show_analyze($(this).val());
        }
    });

    if(typeof game_list != "undefined")
    {
        pager.show_game_list(game_list);
    }
});

/* board.js */

 /**
 * @author xsir317@gmail.com
 * @license http://creativecommons.org/licenses/by-sa/3.0/deed.zh
 */
let board = null;
let boardObj = function()
{
    //棋盘的DOM对象，基本上棋子、棋盘逻辑都在这里面。
    let board = $("#board_main");

    let _obj = this;

    //整个游戏的数据结构，包括对局进程、状态、双方等等。会通过页面变量或者Websocket推过来。
    _obj.gameData = {};

    //字符串，当前局面记录。
    _obj.currgame = '';

    //字符串，记录终局状态。
    _obj.endgame = '';

    // 当前颜色，在初始化时会初始化为黑色
    _obj.curr_color = 'black';

    //当前手数，会被初始化为1
    _obj.curr_step = 1;

    //以下两个变量表示“是否是我参与的游戏”、“当前是否轮到我下棋”
    _obj.is_my_game = false;
    _obj.is_my_turn = false;

    //load 一个游戏数据。
    _obj.load = function( game_data ){
        //为了播放声音，这里对比一下旧盘面和新load的盘面，决定是否播放一次声音
        let play_sound = (_obj.currgame != game_data.game_record);
        _obj.gameData = game_data;
        _obj.show_origin();
        if(play_sound)
        {
            pager.play_sound('Move');
        }
    };

    //setInterval就存在这里，初始化的时候clear一下
    //_obj.timer_handler = 0;

    /**
     * 用于展示时间。 如果对局正在进行，还会负责进行倒计时的显示。
     * 这是一个闭包结构。timer_handler在闭包里。
     */
    _obj.timer = (function(){
        let timer_handler = 0;

        return (function(){
            //首先，获取当前时间，当前游戏的双方剩余时间
            let render_time = function(seconds,player)
            {
                seconds = (seconds > 0) ? seconds : 0;
                let hours = parseInt(seconds/3600).toString();
                if(hours.length == 1) {hours = '0' + hours}
                let minutes = parseInt( (seconds%3600) /60).toString();
                if(minutes.length == 1) {minutes = '0' + minutes}
                let seconds_display = parseInt(seconds % 60).toString();
                if(seconds_display.length == 1) {seconds_display = '0' + seconds_display}

                let display_obj = player ? $("#black_time_display") : $("#white_time_display");
                display_obj.html(hours + ':' + minutes + ':' + seconds_display);
            };

            // 记录当前时间。
            let timer_start = new Date().getTime();
            //先render双方时间显示
            render_time(_obj.gameData.black_time,1);
            render_time(_obj.gameData.white_time,0);
            //如果对局进行中，那么 setInterval 每一秒钟，计算开始时间到当前过了多久；用行棋方时间减去已用时间，再次render。
            //如果对局正在进行中
            if(timer_handler)
            {
                debug_log("we do cleared " + timer_handler + ". we will set up new Interval if needed.");
                clearInterval(timer_handler);
            }
            if(_obj.gameData.status == 1)
            {
                timer_handler = setInterval(function(){
                    let current = new Date().getTime();

                    let delta_time = current - timer_start;
                    let time_left = (_obj.gameData.turn ? _obj.gameData.black_time : _obj.gameData.white_time) - parseInt(delta_time/1000);
                    render_time(time_left,_obj.gameData.turn);
                    if(time_left <= 0)
                    {
                        _obj.notice_timeout();
                    }
                },1000);
                debug_log("setInterval " + timer_handler);
            }
        });
    })();

    //自动切换模式。
    _obj.switch_mode = (function(){
        let _mode = 'game';// game or analyze
        return function(mode,do_switch){
            if(mode == _mode)
            {
                return true;
            }
            if(typeof do_switch == 'boolean' && !do_switch)
            {
                return false;
            }
            _mode = mode;
            switch(mode)
            {
                case 'game':
                    //board.removeClass("mode_analyze").addClass("mode_game");
                    board.css("background-image","url(/images/board.png)");
                    break;
                case 'analyze':
                    pager.show_msg(pager.t('Switched to Analyze mode, you can use the board freely.'));
                    board.css("background-image","url(/images/board-grey.png)");
                    //board.removeClass("mode_game").addClass("mode_analyze");
                    break;
                default:
                    break;
            }
        };
    })();

    /**
     * @description 在指定位置放置一枚棋子。当操作者是行棋一方时，会转交给make_move来处理。
     * 当操作者是玩家之一时，不可以拿棋盘来拆棋，只能按照对局记录前进后退。
     * @param  {string} coordinate 传入坐标。
     * @param  {boolean} play_sound 是否播放声音
     * @returns {boolean}
     */
    _obj.place_stone = function(coordinate,play_sound){
        let target_cell = board.find('.'+coordinate);
        if(!target_cell.hasClass('blank'))
        {
            return false;
        }

        //这里的逻辑解释一下： 如果是轮到我下，而且是完全展示棋局的状态，那么就是“落子状态”。
        //如果是落子状态，就可以不按照之前的记录落下新的一个棋子。
        //如果不是落子状态，则对对局双方作出限制：只能按照之前的记录去落子，不能拿这个棋盘来拆棋。
        let playing = (_obj.is_my_turn && _obj.currgame == _obj.gameData.game_record && !_obj.gameData.waiting_for_a5_number);
        if(_obj.is_my_game && !playing && _obj.gameData.status == 1)
        {
            if(coordinate != _obj.endgame.substr(_obj.currgame.length,2))
            {
                return false;
            }
        }
        //是否显示五手N打点： 第四手展示在棋盘上，并且前4手的确是符合记录的时候，显示打点。
        if(_obj.curr_step == 4 && _obj.endgame == _obj.gameData.game_record)
        {
            _obj.show_a5();
        }
        else if(_obj.curr_step == 5)
        {
            _obj.hide_a5();
        }
        target_cell.removeClass('blank').addClass(_obj.curr_color).html(_obj.curr_step ++);
        _obj.curr_color = (_obj.curr_color == 'black' ? 'white':'black');
        _obj.currgame += coordinate;
        if(_obj.currgame != _obj.endgame.substr(0,_obj.currgame.length))
        {
            _obj.endgame = _obj.currgame;
            //在改变了endgame时，如果不是playing ,则都进入研究模式。
            if(!playing)
            {
                _obj.switch_mode('analyze');
            }
        }
        if(play_sound)
        {
            pager.play_sound('Move');
        }

        //最后，如果是落子状态，通知一下服务器。
        if(playing)
        {
            return _obj.make_move(coordinate);
        }

        return true;
    };

    _obj.show_analyze = function(board_str){
        //不允许对弈棋手使用此方法。
        if(_obj.is_my_game && _obj.gameData.status == 1)
        {
            return false;
        }
        _obj.switch_mode('analyze');
        _obj.board_clean();
        _obj.endgame = board_str;
        _obj.board_end();
    };

    _obj.get_current_board = function () {
        return _obj.currgame;
    };

    /**
     * @description
     * @param  {string} coordinate 传入坐标。
     * @returns {boolean}
     */
    _obj.make_move = function(coordinate){
        if(!_obj.is_my_turn)
        {
            return false;
        }
        $.post(
            "/games/play/play",
            {
                coordinate:coordinate,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
                game_id:gameObj.id
            },
            function(_data){
                if(_data.code != 200)
                {
                    layer.alert(_data.msg);
                    _obj.show_origin();
                }
            },
            "json"
        );
        return true;
    };

    /**
     * 右键和回退按钮的事件，往回退一个棋子。并不产生任何Ajax，这不是悔棋操作。
     * @returns {boolean}
     */
    _obj.move_pre = function(){
        if(_obj.currgame)
        {
            let last_move = _obj.currgame.substr(_obj.currgame.length-2,2);
            //这个棋子拿起来。。。
            let target_cell = board.find('.'+last_move);
            target_cell.removeClass('black white').addClass('blank').html('');
            _obj.curr_step --;
            _obj.curr_color = (_obj.curr_color == 'black' ? 'white':'black');
            _obj.currgame = _obj.currgame.substr(0,_obj.currgame.length-2);
            //打点显示
            if(_obj.curr_step == 5 && _obj.endgame == _obj.gameData.game_record)
            {
                _obj.show_a5();
            }
            else if(_obj.curr_step == 4)
            {
                _obj.hide_a5();
            }
            return true;
        }
        return false;
    };

    /**
     * 根据endgame，一步一步走下去，把整个棋局展示出来。
     * @returns {boolean}
     */
    _obj.move_next = function(){
        if(_obj.currgame != _obj.endgame)
        {
            let nextstep = _obj.endgame.substr(_obj.currgame.length,2);
            _obj.place_stone(nextstep);
            return true;
        }
        return false;
    };

    _obj.notice_timeout = function(){
        $.post("/games/games/timeout",{
            "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
            game_id:gameObj.id
        });
    };
    /**
     * 回退到空棋盘状态。
     */
    _obj.board_clean = function(){
        while (_obj.move_pre()) {}
    };

    /**
     * 根据目前的棋局记录一路Next到局面结束的状态。
     */
    _obj.board_end = function(){
        while(_obj.move_next()) {}
    };

    /**
     * 根据gameData 初始化棋盘的文字信息和棋盘Game信息
     */
    _obj.show_origin = function(){
        _obj.render_game_info();

        _obj.switch_mode('game');
        _obj.board_clean();
        _obj.endgame = _obj.gameData.game_record;
        _obj.board_end();
    };

    /**
     * 展示除了棋盘之外的其他文字信息和对局相关的提示信息。
     * 也负责计算轮到谁落子。
     */
    _obj.render_game_info = (function(){
        let check_game_timer = 0;
        return function(){
            //计算当前是否是“我”落子的回合。
            _obj.is_my_game = false;
            _obj.is_my_turn = false;

            if(userinfo != null)
            {
                _obj.is_my_game = (userinfo.id == _obj.gameData.black_id || userinfo.id == _obj.gameData.white_id);
                _obj.is_my_turn = (_obj.gameData.whom_to_play == userinfo.id);
            }
            $(".black_name>ins").html(_obj.gameData.bplayer.nickname);
            $(".white_name>ins").html(_obj.gameData.wplayer.nickname);
            $(".current_player_name>ins").html(_obj.gameData.turn ? _obj.gameData.bplayer.nickname : _obj.gameData.wplayer.nickname);
            $(".a5_numbers>ins").html(_obj.gameData.a5_numbers);
            $(".is_swap>ins").html(_obj.gameData.swap ? pager.t('Yes'):pager.t('No'));
            $(".game_result>ins>strong").html(pager.t(result_defines[_obj.gameData.status]));
            $(".tara_option1_button").hide()
            if(_obj.is_my_turn)
            {
                _obj.playing_tips();
                let stones = _obj.gameData.game_record.length / 2;
                //tara_option1_button
                if(_obj.gameData.rule == 'TaraGuchi' && stones == 4 && _obj.gameData.a5_numbers > 1 && _obj.gameData.a5_pos.length == 0){
                    $(".tara_option1_button").show()
                }
            }
            else
            {
                $(".turn_to_play_tips").hide();
                $(".swap_button").hide();
            }

            if(_obj.is_my_game && _obj.gameData.status == 1)
            {
                $(".draw_button,.resign_button").show();
            }
            else
            {
                $(".draw_button,.resign_button").hide();
            }
            //undo btn
            if(_obj.is_my_game && _obj.gameData.status == 1 && _obj.gameData.allow_undo)
            {
                $(".undo_button").show();
            }
            else
            {
                $(".undo_button").hide();
            }
            //undo logs
            if(_obj.gameData.undo_log.length > 0)
            {
                $(".undo_records>select").find("option:not(:first)").remove();
                for(let i in _obj.gameData.undo_log)
                {
                    $("<option>").text(
                        _obj.gameData.undo_log[i].user.nickname
                        + ' ' + (_obj.gameData.undo_log[i].current_board.length/2)
                        + ' >> ' +  _obj.gameData.undo_log[i].to_number
                    ).val(_obj.gameData.undo_log[i].current_board).appendTo($(".undo_records>select"));
                }
                $(".undo_records").show();
            }
            else
            {
                $(".undo_records").hide();
            }

            if(_obj.is_my_game && _obj.gameData.status == 1 && _obj.gameData.offer_draw >0 && _obj.gameData.offer_draw != userinfo.id)
            {
                $(".offer_draw_tips").show();
            }
            else
            {
                $(".offer_draw_tips").hide();
            }
            //悔棋
            if(_obj.gameData.undo && _obj.is_my_game && _obj.gameData.status == 1 && _obj.gameData.undo.uid != userinfo.id)
            {
                pager.show_undo(_obj.gameData.undo);
            }

            if(check_game_timer)
            {
                clearInterval(check_game_timer);
            }
            //仅在我是对局者，但当前不轮到我落子的时候，每隔一段时间进行一次检查。这是为了防止Websocket通知失败时，对局者等待导致超时。
            if(_obj.is_my_game && !_obj.is_my_turn && _obj.gameData.status == 1)
            {
                check_game_timer = setInterval(function(){
                    $.getJSON("/games/games/info",{id:_obj.gameData.id,_time:new Date().getTime()},function (_data) {
                        _obj.load(_data.data.game);
                    });
                },15 * 1000);
            }
            //计时
            _obj.timer();
        };
    })();

    _obj.playing_tips = function(){
        if(!_obj.is_my_turn)
        {
            return false;
        }
        let stones = _obj.gameData.game_record.length / 2;
        let tips = pager.t("Your turn to play") + " " + (stones + 1) + pager.t("th move");
        //按照不同规则去写提示。
        switch (_obj.gameData.rule)
        {
            case 'RIF':
            case 'Yamaguchi':
                if(stones < 3)
                {
                    tips = pager.t('Please play the first 3 moves.');
                }
                if (_obj.gameData.can_swap)
                {
                    tips += pager.t(",Or swap");
                }
                if(stones == 4 && _obj.gameData.a5_numbers == (_obj.gameData.a5_pos.length/2))//打点摆完了，等白棋选。
                {
                    tips = pager.t('Please choose one 5th point as the 5th move.');
                }
                else if(stones == 4 && _obj.gameData.a5_numbers > (_obj.gameData.a5_pos.length/2))//打点没摆完
                {
                    tips = pager.t('Please choose ') + _obj.gameData.a5_numbers + pager.t(' points as 5th move');
                }
                break;
            case 'Soosyrv8'://索索夫规则描述 三手可交换，第四手时声明打点数量，可交换。其余略。
                if(stones < 3)
                {
                    tips = pager.t('Please play the first 3 moves.');
                }
                if (_obj.gameData.can_swap)
                {
                    tips += pager.t(",Or swap");
                }
                if(stones == 4 && _obj.gameData.a5_numbers > 0 )
                {
                    if(_obj.gameData.a5_numbers > (_obj.gameData.a5_pos.length/2))
                    {
                        tips = pager.t('Please choose ') + _obj.gameData.a5_numbers + pager.t(' points as 5th move');
                    }
                    if(_obj.gameData.can_swap)
                    {
                        tips += pager.t(",Or swap");
                    }

                    if(_obj.gameData.a5_numbers == (_obj.gameData.a5_pos.length/2))
                    {
                        tips = pager.t('Please choose one 5th point as the 5th move.');
                    }
                }
                break;
            case 'TaraGuchi':
                //落下前4手
                if(stones == 4){
                    //棋盘上4个棋子， 此时如果黑可交换， 那么就是没交换状态， 此时：
                    // 可选交换， 也可下打点。
                    //如果此时不可交换， 那就是黑棋交换过了， 此时9×9方格内落下第5手棋。 然后对方可交换
                    if(_obj.gameData.a5_numbers > (_obj.gameData.a5_pos.length/2))
                    {
                        tips = pager.t('Please choose ') + _obj.gameData.a5_numbers + pager.t(' points as 5th move');
                    }
                    if(_obj.gameData.a5_numbers > 1 && _obj.gameData.a5_pos == ''){
                        tips += pager.t(",Or press the Option1 button, then black may choose ONE 5th move,and opponent can swap");
                    }
                }
                if (_obj.gameData.can_swap)
                {
                    tips += pager.t(",Or swap");
                }

        }

        if(_obj.gameData.waiting_for_a5_number)
        {
            tips = pager.t("How many 5th would you offer");
            pager.ask_for_a5();
        }
        $(".turn_to_play_tips").text(tips).show();
        if(_obj.gameData.can_swap)
        {
            $(".swap_button").show();
        }
        else
        {
            $(".swap_button").hide();
        }
    };

    /**
     * 显示和隐藏五手打点
     * @returns {boolean}
     */
    _obj.show_a5 = function(){
        if(_obj.gameData.a5_pos == '')
            return false;
        let a5_points = '.' + _obj.gameData.a5_pos.substr(0,2);
        for(let sub = 2;sub<_obj.gameData.a5_pos.length;sub += 2)
        {
            a5_points += ',.';
            a5_points += _obj.gameData.a5_pos.substr(sub,2);
        }
        $(a5_points).addClass('black a5stone').html('▲');
    };
    _obj.hide_a5 = function(){
        $(".a5stone").removeClass('black a5stone').html('');
    };
    _obj.show_rule = function () {
        const rule_description = { // 这里不影响游戏的先不翻译了。。。
            Yamaguchi:"山口规则：<br /><p>1.先手方下3个棋子（黑1，白2，黑3），同时指定第五手的打点数量N；</p><p>2.后手方可以选择执白或者执黑。</p> <p>3.白方下第四手；</p> <p>4.黑方按照约定的五手打点数量放上N个棋子，白方指定其中的一个为实战的第五手，然后白方下第六手；</p> <p>5.双方轮流行棋。</p> <p>注意：先手方的开局仅限26种开局。</p>",
            RIF:"RIF规则：<br /><p>1.先手方下3个棋子（黑1，白2，黑3）；</p> <p>2.后手方可以选择执白或者执黑。</p> <p>3.白方下第四手；</p> <p>4.黑方放上2个棋子，白方指定其中的一个为实战的第五手，然后白方下第六手；</p> <p>5.双方轮流行棋。</p> <p>注意：先手方的开局仅限26种开局。</p>",
            Soosyrv8:"索索夫8规则：<br /><p>1.先手方下3个棋子（黑1，白2，黑3，26种开局）；</p> <p>2.后手方可以选择执白或者执黑。</p> <p>3.白方下第四手，同时指定第五手的打点数量N（N<=8）；</p> <p>4.黑方可以选择交换，或者按照约定的五手打点数量放上N个棋子，白方指定其中的一个为实战的第五手，然后白方下第六手；</p> <p>5.双方轮流行棋。</p>",
            Renju:"有禁手规则：<br />黑白双方轮流落子，先5为胜，黑方不得双3，双4，长连，白方长连视为五连。",
            Gomoku:"无禁手规则：<br />双方轮流行棋。黑白双方均无限制，先5为胜，超过6个不产生胜负。",
            TaraGuchi:"塔拉-山口规则：<br /><p>1.前4手落子后都可以交换</p><p>2.第5手有2个选项， 选项1：打点数为1，白可交换；</p> <p>选项2：打点数为10，白不可交换；</p><p>后续类似山口规则</p> <p>3.双方轮流行棋。</p> <p>注意：先手方的开局仅限26种开局。",
        };
        if(typeof rule_description[gameObj.rule] != "undefined")
        {
            pager.show_msg(rule_description[gameObj.rule]);
        }
    };


    /**
     * 画棋盘和按钮。绑定右键事件。
     * 整个页面载入的时候会执行一次。仅此一次。
     */
    _obj.init_board = function(){
        _obj.currgame = '';
        _obj.curr_color = 'black';
        _obj.curr_step = 1;
        board.html('');

        board.mousedown(function(e){
            if(e.which == 3)
            {
                _obj.move_pre();
                return false;
            }
        });
        board.bind("contextmenu", function() { return false; });
        for(let i=1;i<=15;i++)
        {
            //insert a row
            let newrow = $(document.createElement("div"));
            newrow.addClass('row');
            for(let j=1;j<=15;j++)
            {
                //insert a cross point
                let newcell = $(document.createElement("div"));
                newcell.addClass(i.toString(16) + j.toString(16));
                newcell.attr('alt',i.toString(16) + j.toString(16));
                newcell.addClass('blank');
                newrow.append(newcell);
            }
            board.append(newrow);
        }
        board.find('.row div').click(function(){
            _obj.place_stone($(this).attr('alt'),true);
            return true;
        });
        //生成控制按钮
        let controlbar = $(document.createElement("div"));
        controlbar.addClass('controlbar');
        board.after(controlbar);
        //按钮
        $(document.createElement("button")).addClass('button').text('<')  .click(_obj.move_pre   ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('>')  .click(_obj.move_next  ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('|<<')  .click(_obj.board_clean).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('>>|').click(_obj.board_end  ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text(pager.t('Restore'))    .click(_obj.show_origin).appendTo(controlbar);
        $(document.createElement("button")).addClass('button show').text(pager.t('Hide Numbers')).click(function(){
            let _btn = $(this);
            if(_btn.hasClass("show"))
            {
                _btn.text(pager.t('Show Numbers')).removeClass('show');
                $("<style>").attr("id",'hide_number').html('.row div{text-indent:-999px;overflow:hidden;}').appendTo("head");
            }
            else
            {
                _btn.text(pager.t('Hide Numbers')).addClass('show');
                $("#hide_number").remove();
            }
        }).appendTo(controlbar);
    };
};

if(typeof gameObj == 'object')
{
//1.new出对象
    board = new boardObj();

    $(document).ready(function(){
//页面初始化时对棋盘的操作：
//2.调用其init方法
        board.init_board();
//3.把web页输出的数据结构load进来。
        board.load(gameObj);
        board.show_rule();
    });
}

/* swfobject.js */

 /*	SWFObject v2.2 <http://code.google.com/p/swfobject/> 
	is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
var swfobject=function(){var D="undefined",r="object",S="Shockwave Flash",W="ShockwaveFlash.ShockwaveFlash",q="application/x-shockwave-flash",R="SWFObjectExprInst",x="onreadystatechange",O=window,j=document,t=navigator,T=false,U=[h],o=[],N=[],I=[],l,Q,E,B,J=false,a=false,n,G,m=true,M=function(){var aa=typeof j.getElementById!=D&&typeof j.getElementsByTagName!=D&&typeof j.createElement!=D,ah=t.userAgent.toLowerCase(),Y=t.platform.toLowerCase(),ae=Y?/win/.test(Y):/win/.test(ah),ac=Y?/mac/.test(Y):/mac/.test(ah),af=/webkit/.test(ah)?parseFloat(ah.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):false,X=!+"\v1",ag=[0,0,0],ab=null;if(typeof t.plugins!=D&&typeof t.plugins[S]==r){ab=t.plugins[S].description;if(ab&&!(typeof t.mimeTypes!=D&&t.mimeTypes[q]&&!t.mimeTypes[q].enabledPlugin)){T=true;X=false;ab=ab.replace(/^.*\s+(\S+\s+\S+$)/,"$1");ag[0]=parseInt(ab.replace(/^(.*)\..*$/,"$1"),10);ag[1]=parseInt(ab.replace(/^.*\.(.*)\s.*$/,"$1"),10);ag[2]=/[a-zA-Z]/.test(ab)?parseInt(ab.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0}}else{if(typeof O.ActiveXObject!=D){try{var ad=new ActiveXObject(W);if(ad){ab=ad.GetVariable("$version");if(ab){X=true;ab=ab.split(" ")[1].split(",");ag=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}}catch(Z){}}}return{w3:aa,pv:ag,wk:af,ie:X,win:ae,mac:ac}}(),k=function(){if(!M.w3){return}if((typeof j.readyState!=D&&j.readyState=="complete")||(typeof j.readyState==D&&(j.getElementsByTagName("body")[0]||j.body))){f()}if(!J){if(typeof j.addEventListener!=D){j.addEventListener("DOMContentLoaded",f,false)}if(M.ie&&M.win){j.attachEvent(x,function(){if(j.readyState=="complete"){j.detachEvent(x,arguments.callee);f()}});if(O==top){(function(){if(J){return}try{j.documentElement.doScroll("left")}catch(X){setTimeout(arguments.callee,0);return}f()})()}}if(M.wk){(function(){if(J){return}if(!/loaded|complete/.test(j.readyState)){setTimeout(arguments.callee,0);return}f()})()}s(f)}}();function f(){if(J){return}try{var Z=j.getElementsByTagName("body")[0].appendChild(C("span"));Z.parentNode.removeChild(Z)}catch(aa){return}J=true;var X=U.length;for(var Y=0;Y<X;Y++){U[Y]()}}function K(X){if(J){X()}else{U[U.length]=X}}function s(Y){if(typeof O.addEventListener!=D){O.addEventListener("load",Y,false)}else{if(typeof j.addEventListener!=D){j.addEventListener("load",Y,false)}else{if(typeof O.attachEvent!=D){i(O,"onload",Y)}else{if(typeof O.onload=="function"){var X=O.onload;O.onload=function(){X();Y()}}else{O.onload=Y}}}}}function h(){if(T){V()}else{H()}}function V(){var X=j.getElementsByTagName("body")[0];var aa=C(r);aa.setAttribute("type",q);var Z=X.appendChild(aa);if(Z){var Y=0;(function(){if(typeof Z.GetVariable!=D){var ab=Z.GetVariable("$version");if(ab){ab=ab.split(" ")[1].split(",");M.pv=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}else{if(Y<10){Y++;setTimeout(arguments.callee,10);return}}X.removeChild(aa);Z=null;H()})()}else{H()}}function H(){var ag=o.length;if(ag>0){for(var af=0;af<ag;af++){var Y=o[af].id;var ab=o[af].callbackFn;var aa={success:false,id:Y};if(M.pv[0]>0){var ae=c(Y);if(ae){if(F(o[af].swfVersion)&&!(M.wk&&M.wk<312)){w(Y,true);if(ab){aa.success=true;aa.ref=z(Y);ab(aa)}}else{if(o[af].expressInstall&&A()){var ai={};ai.data=o[af].expressInstall;ai.width=ae.getAttribute("width")||"0";ai.height=ae.getAttribute("height")||"0";if(ae.getAttribute("class")){ai.styleclass=ae.getAttribute("class")}if(ae.getAttribute("align")){ai.align=ae.getAttribute("align")}var ah={};var X=ae.getElementsByTagName("param");var ac=X.length;for(var ad=0;ad<ac;ad++){if(X[ad].getAttribute("name").toLowerCase()!="movie"){ah[X[ad].getAttribute("name")]=X[ad].getAttribute("value")}}P(ai,ah,Y,ab)}else{p(ae);if(ab){ab(aa)}}}}}else{w(Y,true);if(ab){var Z=z(Y);if(Z&&typeof Z.SetVariable!=D){aa.success=true;aa.ref=Z}ab(aa)}}}}}function z(aa){var X=null;var Y=c(aa);if(Y&&Y.nodeName=="OBJECT"){if(typeof Y.SetVariable!=D){X=Y}else{var Z=Y.getElementsByTagName(r)[0];if(Z){X=Z}}}return X}function A(){return !a&&F("6.0.65")&&(M.win||M.mac)&&!(M.wk&&M.wk<312)}function P(aa,ab,X,Z){a=true;E=Z||null;B={success:false,id:X};var ae=c(X);if(ae){if(ae.nodeName=="OBJECT"){l=g(ae);Q=null}else{l=ae;Q=X}aa.id=R;if(typeof aa.width==D||(!/%$/.test(aa.width)&&parseInt(aa.width,10)<310)){aa.width="310"}if(typeof aa.height==D||(!/%$/.test(aa.height)&&parseInt(aa.height,10)<137)){aa.height="137"}j.title=j.title.slice(0,47)+" - Flash Player Installation";var ad=M.ie&&M.win?"ActiveX":"PlugIn",ac="MMredirectURL="+O.location.toString().replace(/&/g,"%26")+"&MMplayerType="+ad+"&MMdoctitle="+j.title;if(typeof ab.flashvars!=D){ab.flashvars+="&"+ac}else{ab.flashvars=ac}if(M.ie&&M.win&&ae.readyState!=4){var Y=C("div");X+="SWFObjectNew";Y.setAttribute("id",X);ae.parentNode.insertBefore(Y,ae);ae.style.display="none";(function(){if(ae.readyState==4){ae.parentNode.removeChild(ae)}else{setTimeout(arguments.callee,10)}})()}u(aa,ab,X)}}function p(Y){if(M.ie&&M.win&&Y.readyState!=4){var X=C("div");Y.parentNode.insertBefore(X,Y);X.parentNode.replaceChild(g(Y),X);Y.style.display="none";(function(){if(Y.readyState==4){Y.parentNode.removeChild(Y)}else{setTimeout(arguments.callee,10)}})()}else{Y.parentNode.replaceChild(g(Y),Y)}}function g(ab){var aa=C("div");if(M.win&&M.ie){aa.innerHTML=ab.innerHTML}else{var Y=ab.getElementsByTagName(r)[0];if(Y){var ad=Y.childNodes;if(ad){var X=ad.length;for(var Z=0;Z<X;Z++){if(!(ad[Z].nodeType==1&&ad[Z].nodeName=="PARAM")&&!(ad[Z].nodeType==8)){aa.appendChild(ad[Z].cloneNode(true))}}}}}return aa}function u(ai,ag,Y){var X,aa=c(Y);if(M.wk&&M.wk<312){return X}if(aa){if(typeof ai.id==D){ai.id=Y}if(M.ie&&M.win){var ah="";for(var ae in ai){if(ai[ae]!=Object.prototype[ae]){if(ae.toLowerCase()=="data"){ag.movie=ai[ae]}else{if(ae.toLowerCase()=="styleclass"){ah+=' class="'+ai[ae]+'"'}else{if(ae.toLowerCase()!="classid"){ah+=" "+ae+'="'+ai[ae]+'"'}}}}}var af="";for(var ad in ag){if(ag[ad]!=Object.prototype[ad]){af+='<param name="'+ad+'" value="'+ag[ad]+'" />'}}aa.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+ah+">"+af+"</object>";N[N.length]=ai.id;X=c(ai.id)}else{var Z=C(r);Z.setAttribute("type",q);for(var ac in ai){if(ai[ac]!=Object.prototype[ac]){if(ac.toLowerCase()=="styleclass"){Z.setAttribute("class",ai[ac])}else{if(ac.toLowerCase()!="classid"){Z.setAttribute(ac,ai[ac])}}}}for(var ab in ag){if(ag[ab]!=Object.prototype[ab]&&ab.toLowerCase()!="movie"){e(Z,ab,ag[ab])}}aa.parentNode.replaceChild(Z,aa);X=Z}}return X}function e(Z,X,Y){var aa=C("param");aa.setAttribute("name",X);aa.setAttribute("value",Y);Z.appendChild(aa)}function y(Y){var X=c(Y);if(X&&X.nodeName=="OBJECT"){if(M.ie&&M.win){X.style.display="none";(function(){if(X.readyState==4){b(Y)}else{setTimeout(arguments.callee,10)}})()}else{X.parentNode.removeChild(X)}}}function b(Z){var Y=c(Z);if(Y){for(var X in Y){if(typeof Y[X]=="function"){Y[X]=null}}Y.parentNode.removeChild(Y)}}function c(Z){var X=null;try{X=j.getElementById(Z)}catch(Y){}return X}function C(X){return j.createElement(X)}function i(Z,X,Y){Z.attachEvent(X,Y);I[I.length]=[Z,X,Y]}function F(Z){var Y=M.pv,X=Z.split(".");X[0]=parseInt(X[0],10);X[1]=parseInt(X[1],10)||0;X[2]=parseInt(X[2],10)||0;return(Y[0]>X[0]||(Y[0]==X[0]&&Y[1]>X[1])||(Y[0]==X[0]&&Y[1]==X[1]&&Y[2]>=X[2]))?true:false}function v(ac,Y,ad,ab){if(M.ie&&M.mac){return}var aa=j.getElementsByTagName("head")[0];if(!aa){return}var X=(ad&&typeof ad=="string")?ad:"screen";if(ab){n=null;G=null}if(!n||G!=X){var Z=C("style");Z.setAttribute("type","text/css");Z.setAttribute("media",X);n=aa.appendChild(Z);if(M.ie&&M.win&&typeof j.styleSheets!=D&&j.styleSheets.length>0){n=j.styleSheets[j.styleSheets.length-1]}G=X}if(M.ie&&M.win){if(n&&typeof n.addRule==r){n.addRule(ac,Y)}}else{if(n&&typeof j.createTextNode!=D){n.appendChild(j.createTextNode(ac+" {"+Y+"}"))}}}function w(Z,X){if(!m){return}var Y=X?"visible":"hidden";if(J&&c(Z)){c(Z).style.visibility=Y}else{v("#"+Z,"visibility:"+Y)}}function L(Y){var Z=/[\\\"<>\.;]/;var X=Z.exec(Y)!=null;return X&&typeof encodeURIComponent!=D?encodeURIComponent(Y):Y}var d=function(){if(M.ie&&M.win){window.attachEvent("onunload",function(){var ac=I.length;for(var ab=0;ab<ac;ab++){I[ab][0].detachEvent(I[ab][1],I[ab][2])}var Z=N.length;for(var aa=0;aa<Z;aa++){y(N[aa])}for(var Y in M){M[Y]=null}M=null;for(var X in swfobject){swfobject[X]=null}swfobject=null})}}();return{registerObject:function(ab,X,aa,Z){if(M.w3&&ab&&X){var Y={};Y.id=ab;Y.swfVersion=X;Y.expressInstall=aa;Y.callbackFn=Z;o[o.length]=Y;w(ab,false)}else{if(Z){Z({success:false,id:ab})}}},getObjectById:function(X){if(M.w3){return z(X)}},embedSWF:function(ab,ah,ae,ag,Y,aa,Z,ad,af,ac){var X={success:false,id:ah};if(M.w3&&!(M.wk&&M.wk<312)&&ab&&ah&&ae&&ag&&Y){w(ah,false);K(function(){ae+="";ag+="";var aj={};if(af&&typeof af===r){for(var al in af){aj[al]=af[al]}}aj.data=ab;aj.width=ae;aj.height=ag;var am={};if(ad&&typeof ad===r){for(var ak in ad){am[ak]=ad[ak]}}if(Z&&typeof Z===r){for(var ai in Z){if(typeof am.flashvars!=D){am.flashvars+="&"+ai+"="+Z[ai]}else{am.flashvars=ai+"="+Z[ai]}}}if(F(Y)){var an=u(aj,am,ah);if(aj.id==ah){w(ah,true)}X.success=true;X.ref=an}else{if(aa&&A()){aj.data=aa;P(aj,am,ah,ac);return}else{w(ah,true)}}if(ac){ac(X)}})}else{if(ac){ac(X)}}},switchOffAutoHideShow:function(){m=false},ua:M,getFlashPlayerVersion:function(){return{major:M.pv[0],minor:M.pv[1],release:M.pv[2]}},hasFlashPlayerVersion:F,createSWF:function(Z,Y,X){if(M.w3){return u(Z,Y,X)}else{return undefined}},showExpressInstall:function(Z,aa,X,Y){if(M.w3&&A()){P(Z,aa,X,Y)}},removeSWF:function(X){if(M.w3){y(X)}},createCSS:function(aa,Z,Y,X){if(M.w3){v(aa,Z,Y,X)}},addDomLoadEvent:K,addLoadEvent:s,getQueryParamValue:function(aa){var Z=j.location.search||j.location.hash;if(Z){if(/\?/.test(Z)){Z=Z.split("?")[1]}if(aa==null){return L(Z)}var Y=Z.split("&");for(var X=0;X<Y.length;X++){if(Y[X].substring(0,Y[X].indexOf("="))==aa){return L(Y[X].substring((Y[X].indexOf("=")+1)))}}}return""},expressInstallCallback:function(){if(a){var X=c(R);if(X&&l){X.parentNode.replaceChild(l,X);if(Q){w(Q,true);if(M.ie&&M.win){l.style.display="block"}}if(E){E(B)}}a=false}}}}();
/* web_socket.js */

 // Copyright: Hiroshi Ichikawa <http://gimite.net/en/>
// License: New BSD License
// Reference: http://dev.w3.org/html5/websockets/
// Reference: http://tools.ietf.org/html/rfc6455
(function() {
  if (window.WEB_SOCKET_FORCE_FLASH) {
    // Keeps going.
  } else if (window.WebSocket) {
    return;
  } else if (window.MozWebSocket) {
    // Firefox.
    window.WebSocket = MozWebSocket;
    return;
  }
  
  var logger;
  if (window.WEB_SOCKET_LOGGER) {
    logger = WEB_SOCKET_LOGGER;
  } else if (window.console && window.console.log && window.console.error) {
    // In some environment, console is defined but console.log or console.error is missing.
    logger = window.console;
    //logger = {log: function(msg){alert("logger.log  "+msg) }, error: function(msg){alert("logger.error  "+msg) }};
  } else {
    logger = {log: function(){ }, error: function(){ }};
  }
  
  // swfobject.hasFlashPlayerVersion("10.0.0") doesn't work with Gnash.
  if (swfobject.getFlashPlayerVersion().major < 10) {
    logger.error("Flash Player >= 10.0.0 is required.");
    return;
  }
  if (location.protocol == "file:") {
    logger.error(
      "WARNING: web-socket-js doesn't work in file:///... URL " +
      "unless you set Flash Security Settings properly. " +
      "Open the page via Web server i.e. http://...");
  }

  /**
   * Our own implementation of WebSocket class using Flash.
   * @param {string} url
   * @param {array or string} protocols
   * @param {string} proxyHost
   * @param {int} proxyPort
   * @param {string} headers
   */
  window.WebSocket = function(url, protocols, proxyHost, proxyPort, headers) {
    var self = this;
    self.__id = WebSocket.__nextId++;
    WebSocket.__instances[self.__id] = self;
    self.readyState = WebSocket.CONNECTING;
    self.bufferedAmount = 0;
    self.__events = {};
    if (!protocols) {
      protocols = [];
    } else if (typeof protocols == "string") {
      protocols = [protocols];
    }
    self.flashCreate=null;
    // Uses setTimeout() to make sure __createFlash() runs after the caller sets ws.onopen etc.
    // Otherwise, when onopen fires immediately, onopen is called before it is set.
    self.__createTask = setTimeout(function() {
      WebSocket.__addTask(function() {
        self.__createTask = null;
        WebSocket.__flash.create(
            self.__id, url, protocols, proxyHost || null, proxyPort || 0, headers || null);
      });
    }, 0);
  };

  /**
   * Send data to the web socket.
   * @param {string} data  The data to send to the socket.
   * @return {boolean}  True for success, false for failure.
   */
  WebSocket.prototype.send = function(data) {
    if (this.readyState == WebSocket.CONNECTING) {
      throw "INVALID_STATE_ERR: Web Socket connection has not been established";
    }
    // We use encodeURIComponent() here, because FABridge doesn't work if
    // the argument includes some characters. We don't use escape() here
    // because of this:
    // https://developer.mozilla.org/en/Core_JavaScript_1.5_Guide/Functions#escape_and_unescape_Functions
    // But it looks decodeURIComponent(encodeURIComponent(s)) doesn't
    // preserve all Unicode characters either e.g. "\uffff" in Firefox.
    // Note by wtritch: Hopefully this will not be necessary using ExternalInterface.  Will require
    // additional testing.
    var result = WebSocket.__flash.send(this.__id, encodeURIComponent(data));
    if (result < 0) { // success
      return true;
    } else {
      this.bufferedAmount += result;
      return false;
    }
  };

  /**
   * Close this web socket gracefully.
   */
  WebSocket.prototype.close = function() {
    if (this.__createTask) {
      clearTimeout(this.__createTask);
      this.__createTask = null;
      this.readyState = WebSocket.CLOSED;
      return;
    }
    if (this.readyState == WebSocket.CLOSED || this.readyState == WebSocket.CLOSING) {
      return;
    }
    this.readyState = WebSocket.CLOSING;
    WebSocket.__flash.close(this.__id);
  };

  /**
   * Implementation of {@link <a href="http://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-registration">DOM 2 EventTarget Interface</a>}
   *
   * @param {string} type
   * @param {function} listener
   * @param {boolean} useCapture
   * @return void
   */
  WebSocket.prototype.addEventListener = function(type, listener, useCapture) {
    if (!(type in this.__events)) {
      this.__events[type] = [];
    }
    this.__events[type].push(listener);
  };

  /**
   * Implementation of {@link <a href="http://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-registration">DOM 2 EventTarget Interface</a>}
   *
   * @param {string} type
   * @param {function} listener
   * @param {boolean} useCapture
   * @return void
   */
  WebSocket.prototype.removeEventListener = function(type, listener, useCapture) {
    if (!(type in this.__events)) return;
    var events = this.__events[type];
    for (var i = events.length - 1; i >= 0; --i) {
      if (events[i] === listener) {
        events.splice(i, 1);
        break;
      }
    }
  };

  /**
   * Implementation of {@link <a href="http://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-registration">DOM 2 EventTarget Interface</a>}
   *
   * @param {Event} event
   * @return void
   */
  WebSocket.prototype.dispatchEvent = function(event) {
    var events = this.__events[event.type] || [];
    for (var i = 0; i < events.length; ++i) {
      events[i](event);
    }
    var handler = this["on" + event.type];
    if (handler) handler.apply(this, [event]);
  };

  /**
   * Handles an event from Flash.
   * @param {Object} flashEvent
   */
  WebSocket.prototype.__handleEvent = function(flashEvent) {
    
    if ("readyState" in flashEvent) {
      this.readyState = flashEvent.readyState;
    }
    if ("protocol" in flashEvent) {
      this.protocol = flashEvent.protocol;
    }
    
    var jsEvent;
    if (flashEvent.type == "open" || flashEvent.type == "error") {
      jsEvent = this.__createSimpleEvent(flashEvent.type);
    } else if (flashEvent.type == "close") {
      jsEvent = this.__createSimpleEvent("close");
      jsEvent.wasClean = flashEvent.wasClean ? true : false;
      jsEvent.code = flashEvent.code;
      jsEvent.reason = flashEvent.reason;
    } else if (flashEvent.type == "message") {
      var data = decodeURIComponent(flashEvent.message);
      jsEvent = this.__createMessageEvent("message", data);
    } else {
      throw "unknown event type: " + flashEvent.type;
    }
    
    this.dispatchEvent(jsEvent);
    
  };
  
  WebSocket.prototype.__createSimpleEvent = function(type) {
    if (document.createEvent && window.Event) {
      var event = document.createEvent("Event");
      event.initEvent(type, false, false);
      return event;
    } else {
      return {type: type, bubbles: false, cancelable: false};
    }
  };
  
  WebSocket.prototype.__createMessageEvent = function(type, data) {
    if (window.MessageEvent && typeof(MessageEvent) == "function" && !window.opera) {
      return new MessageEvent("message", {
        "view": window,
        "bubbles": false,
        "cancelable": false,
        "data": data
      });
    } else if (document.createEvent && window.MessageEvent && !window.opera) {
      var event = document.createEvent("MessageEvent");
    	event.initMessageEvent("message", false, false, data, null, null, window, null);
      return event;
    } else {
      // Old IE and Opera, the latter one truncates the data parameter after any 0x00 bytes.
      return {type: type, data: data, bubbles: false, cancelable: false};
    }
  };
  
  /**
   * Define the WebSocket readyState enumeration.
   */
  WebSocket.CONNECTING = 0;
  WebSocket.OPEN = 1;
  WebSocket.CLOSING = 2;
  WebSocket.CLOSED = 3;

  // Field to check implementation of WebSocket.
  WebSocket.__isFlashImplementation = true;
  WebSocket.__initialized = false;
  WebSocket.__flash = null;
  WebSocket.__instances = {};
  WebSocket.__tasks = [];
  WebSocket.__nextId = 0;
  
  /**
   * Load a new flash security policy file.
   * @param {string} url
   */
  WebSocket.loadFlashPolicyFile = function(url){
    WebSocket.__addTask(function() {
      WebSocket.__flash.loadManualPolicyFile(url);
    });
  };

  /**
   * Loads WebSocketMain.swf and creates WebSocketMain object in Flash.
   */
  WebSocket.__initialize = function() {
    
    if (WebSocket.__initialized) return;
    WebSocket.__initialized = true;
    
    if (WebSocket.__swfLocation) {
      // For backword compatibility.
      window.WEB_SOCKET_SWF_LOCATION = WebSocket.__swfLocation;
    }
    if (!window.WEB_SOCKET_SWF_LOCATION) {
      logger.error("[WebSocket] set WEB_SOCKET_SWF_LOCATION to location of WebSocketMain.swf");
      return;
    }
    if (!window.WEB_SOCKET_SUPPRESS_CROSS_DOMAIN_SWF_ERROR &&
        !WEB_SOCKET_SWF_LOCATION.match(/(^|\/)WebSocketMainInsecure\.swf(\?.*)?$/) &&
        WEB_SOCKET_SWF_LOCATION.match(/^\w+:\/\/([^\/]+)/)) {
      var swfHost = RegExp.$1;
      if (location.host != swfHost) {
        logger.error(
            "[WebSocket] You must host HTML and WebSocketMain.swf in the same host " +
            "('" + location.host + "' != '" + swfHost + "'). " +
            "See also 'How to host HTML file and SWF file in different domains' section " +
            "in README.md. If you use WebSocketMainInsecure.swf, you can suppress this message " +
            "by WEB_SOCKET_SUPPRESS_CROSS_DOMAIN_SWF_ERROR = true;");
      }
    }
    var container = document.createElement("div");
    container.id = "webSocketContainer";
    // Hides Flash box. We cannot use display: none or visibility: hidden because it prevents
    // Flash from loading at least in IE. So we move it out of the screen at (-100, -100).
    // But this even doesn't work with Flash Lite (e.g. in Droid Incredible). So with Flash
    // Lite, we put it at (0, 0). This shows 1x1 box visible at left-top corner but this is
    // the best we can do as far as we know now.
    container.style.position = "absolute";
    if (WebSocket.__isFlashLite()) {
      container.style.left = "0px";
      container.style.top = "0px";
    } else {
      container.style.left = "-100px";
      container.style.top = "-100px";
    }
    var holder = document.createElement("div");
    holder.id = "webSocketFlash";
    container.appendChild(holder);
    document.body.appendChild(container);
    // See this article for hasPriority:
    // http://help.adobe.com/en_US/as3/mobile/WS4bebcd66a74275c36cfb8137124318eebc6-7ffd.html
    swfobject.embedSWF(
      WEB_SOCKET_SWF_LOCATION,
      "webSocketFlash",
      "1" /* width */,
      "1" /* height */,
      "10.0.0" /* SWF version */,
      null,
      null,
      {hasPriority: true, swliveconnect : true, allowScriptAccess: "always"},
      null,
      function(e) {
        if (!e.success) {
          logger.error("[WebSocket] swfobject.embedSWF failed");
        }
      }
    );
    
  };
  
  /**
   * Called by Flash to notify JS that it's fully loaded and ready
   * for communication.
   */
  WebSocket.__onFlashInitialized = function() {
    // We need to set a timeout here to avoid round-trip calls
    // to flash during the initialization process.
    setTimeout(function() {
      WebSocket.__flash = document.getElementById("webSocketFlash");
      WebSocket.__flash.setCallerUrl(location.href);
      WebSocket.__flash.setDebug(!!window.WEB_SOCKET_DEBUG);
      for (var i = 0; i < WebSocket.__tasks.length; ++i) {
        WebSocket.__tasks[i]();
      }
      WebSocket.__tasks = [];
    }, 0);
  };
  
  /**
   * Called by Flash to notify WebSockets events are fired.
   */
  WebSocket.__onFlashEvent = function() {
    setTimeout(function() {
      try {
        // Gets events using receiveEvents() instead of getting it from event object
        // of Flash event. This is to make sure to keep message order.
        // It seems sometimes Flash events don't arrive in the same order as they are sent.
        var events = WebSocket.__flash.receiveEvents();
        for (var i = 0; i < events.length; ++i) {
          WebSocket.__instances[events[i].webSocketId].__handleEvent(events[i]);
        }
      } catch (e) {
        logger.error(e);
      }
    }, 0);
    return true;
  };
  
  // Called by Flash.
  WebSocket.__log = function(message) {
    logger.log(decodeURIComponent(message));
  };
  
  // Called by Flash.
  WebSocket.__error = function(message) {
    logger.error(decodeURIComponent(message));
  };
  
  WebSocket.__addTask = function(task) {
    if (WebSocket.__flash) {
      task();
    } else {
      WebSocket.__tasks.push(task);
    }
  };
  
  /**
   * Test if the browser is running flash lite.
   * @return {boolean} True if flash lite is running, false otherwise.
   */
  WebSocket.__isFlashLite = function() {
    if (!window.navigator || !window.navigator.mimeTypes) {
      return false;
    }
    var mimeType = window.navigator.mimeTypes["application/x-shockwave-flash"];
    if (!mimeType || !mimeType.enabledPlugin || !mimeType.enabledPlugin.filename) {
      return false;
    }
    return mimeType.enabledPlugin.filename.match(/flashlite/i) ? true : false;
  };
  if (!window.WEB_SOCKET_DISABLE_AUTO_INITIALIZATION) {
    // NOTE:
    //   This fires immediately if web_socket.js is dynamically loaded after
    //   the document is loaded.
    swfobject.addDomLoadEvent(function() {
      WebSocket.__initialize();
    });
  }
  
})();

/* md5.min.js */

 !function(n){"use strict";function t(n,t){var r=(65535&n)+(65535&t),e=(n>>16)+(t>>16)+(r>>16);return e<<16|65535&r}function r(n,t){return n<<t|n>>>32-t}function e(n,e,o,u,c,f){return t(r(t(t(e,n),t(u,f)),c),o)}function o(n,t,r,o,u,c,f){return e(t&r|~t&o,n,t,u,c,f)}function u(n,t,r,o,u,c,f){return e(t&o|r&~o,n,t,u,c,f)}function c(n,t,r,o,u,c,f){return e(t^r^o,n,t,u,c,f)}function f(n,t,r,o,u,c,f){return e(r^(t|~o),n,t,u,c,f)}function i(n,r){n[r>>5]|=128<<r%32,n[(r+64>>>9<<4)+14]=r;var e,i,a,h,d,l=1732584193,g=-271733879,v=-1732584194,m=271733878;for(e=0;e<n.length;e+=16)i=l,a=g,h=v,d=m,l=o(l,g,v,m,n[e],7,-680876936),m=o(m,l,g,v,n[e+1],12,-389564586),v=o(v,m,l,g,n[e+2],17,606105819),g=o(g,v,m,l,n[e+3],22,-1044525330),l=o(l,g,v,m,n[e+4],7,-176418897),m=o(m,l,g,v,n[e+5],12,1200080426),v=o(v,m,l,g,n[e+6],17,-1473231341),g=o(g,v,m,l,n[e+7],22,-45705983),l=o(l,g,v,m,n[e+8],7,1770035416),m=o(m,l,g,v,n[e+9],12,-1958414417),v=o(v,m,l,g,n[e+10],17,-42063),g=o(g,v,m,l,n[e+11],22,-1990404162),l=o(l,g,v,m,n[e+12],7,1804603682),m=o(m,l,g,v,n[e+13],12,-40341101),v=o(v,m,l,g,n[e+14],17,-1502002290),g=o(g,v,m,l,n[e+15],22,1236535329),l=u(l,g,v,m,n[e+1],5,-165796510),m=u(m,l,g,v,n[e+6],9,-1069501632),v=u(v,m,l,g,n[e+11],14,643717713),g=u(g,v,m,l,n[e],20,-373897302),l=u(l,g,v,m,n[e+5],5,-701558691),m=u(m,l,g,v,n[e+10],9,38016083),v=u(v,m,l,g,n[e+15],14,-660478335),g=u(g,v,m,l,n[e+4],20,-405537848),l=u(l,g,v,m,n[e+9],5,568446438),m=u(m,l,g,v,n[e+14],9,-1019803690),v=u(v,m,l,g,n[e+3],14,-187363961),g=u(g,v,m,l,n[e+8],20,1163531501),l=u(l,g,v,m,n[e+13],5,-1444681467),m=u(m,l,g,v,n[e+2],9,-51403784),v=u(v,m,l,g,n[e+7],14,1735328473),g=u(g,v,m,l,n[e+12],20,-1926607734),l=c(l,g,v,m,n[e+5],4,-378558),m=c(m,l,g,v,n[e+8],11,-2022574463),v=c(v,m,l,g,n[e+11],16,1839030562),g=c(g,v,m,l,n[e+14],23,-35309556),l=c(l,g,v,m,n[e+1],4,-1530992060),m=c(m,l,g,v,n[e+4],11,1272893353),v=c(v,m,l,g,n[e+7],16,-155497632),g=c(g,v,m,l,n[e+10],23,-1094730640),l=c(l,g,v,m,n[e+13],4,681279174),m=c(m,l,g,v,n[e],11,-358537222),v=c(v,m,l,g,n[e+3],16,-722521979),g=c(g,v,m,l,n[e+6],23,76029189),l=c(l,g,v,m,n[e+9],4,-640364487),m=c(m,l,g,v,n[e+12],11,-421815835),v=c(v,m,l,g,n[e+15],16,530742520),g=c(g,v,m,l,n[e+2],23,-995338651),l=f(l,g,v,m,n[e],6,-198630844),m=f(m,l,g,v,n[e+7],10,1126891415),v=f(v,m,l,g,n[e+14],15,-1416354905),g=f(g,v,m,l,n[e+5],21,-57434055),l=f(l,g,v,m,n[e+12],6,1700485571),m=f(m,l,g,v,n[e+3],10,-1894986606),v=f(v,m,l,g,n[e+10],15,-1051523),g=f(g,v,m,l,n[e+1],21,-2054922799),l=f(l,g,v,m,n[e+8],6,1873313359),m=f(m,l,g,v,n[e+15],10,-30611744),v=f(v,m,l,g,n[e+6],15,-1560198380),g=f(g,v,m,l,n[e+13],21,1309151649),l=f(l,g,v,m,n[e+4],6,-145523070),m=f(m,l,g,v,n[e+11],10,-1120210379),v=f(v,m,l,g,n[e+2],15,718787259),g=f(g,v,m,l,n[e+9],21,-343485551),l=t(l,i),g=t(g,a),v=t(v,h),m=t(m,d);return[l,g,v,m]}function a(n){var t,r="",e=32*n.length;for(t=0;e>t;t+=8)r+=String.fromCharCode(n[t>>5]>>>t%32&255);return r}function h(n){var t,r=[];for(r[(n.length>>2)-1]=void 0,t=0;t<r.length;t+=1)r[t]=0;var e=8*n.length;for(t=0;e>t;t+=8)r[t>>5]|=(255&n.charCodeAt(t/8))<<t%32;return r}function d(n){return a(i(h(n),8*n.length))}function l(n,t){var r,e,o=h(n),u=[],c=[];for(u[15]=c[15]=void 0,o.length>16&&(o=i(o,8*n.length)),r=0;16>r;r+=1)u[r]=909522486^o[r],c[r]=1549556828^o[r];return e=i(u.concat(h(t)),512+8*t.length),a(i(c.concat(e),640))}function g(n){var t,r,e="0123456789abcdef",o="";for(r=0;r<n.length;r+=1)t=n.charCodeAt(r),o+=e.charAt(t>>>4&15)+e.charAt(15&t);return o}function v(n){return unescape(encodeURIComponent(n))}function m(n){return d(v(n))}function p(n){return g(m(n))}function s(n,t){return l(v(n),v(t))}function C(n,t){return g(s(n,t))}function A(n,t,r){return t?r?s(t,n):C(t,n):r?m(n):p(n)}"function"==typeof define&&define.amd?define(function(){return A}):"object"==typeof module&&module.exports?module.exports=A:n.md5=A}(this);

/* websocket.js */

 //websocket
Object.keys = Object.keys || function(obj){/**兼容IE**/
    let result = [];
        for(let key in obj )
            if(({}).hasOwnProperty.call(obj,key)){
                result.push(key) ;
            }
        return result;
    };
WEB_SOCKET_SWF_LOCATION = "/swf/WebSocketMain.swf?time="+new Date().getTime();
WEB_SOCKET_DEBUG = true;
let ws = null;

let global_current_client_id = '';
let chat = function (){
    let that=this;
    this.reconnect = '0';
    // 连接服务端
    this.connect = function () {
        if(typeof ws_token == "undefined")
        {
            return false;
        }
        // 创建websocket
        ws = new WebSocket("ws://"+document.domain+":8282");
        // 当socket连接打开时，输入用户名
        ws.onopen = that.onopen;
        // 当有消息时根据消息类型显示不同信息
        ws.onmessage = that.onmessage;
        ws.onclose = function(e) {
            debug_log("连接关闭，定时重连");
            window.setTimeout(that.connect,3000);
        };
        ws.onerror = function(e) {
            debug_log(e);
        };
    };
    this.object_md5 = function (obj) {
        let keys = Object.keys(obj).sort();
        let stringfy = '', prop;
        for (let i = 0; i < keys.length; i++) {
            prop = keys[i];
            if(stringfy != '') stringfy += '&';
            stringfy += (prop + '=' + obj[prop]);
        }
        return md5(stringfy);
    };


    // 连接建立时发送WEBSOCKET登录信息
    this.onopen = function ()
    {
        let login_data = {
            type:"login",
            game_id:typeof gameObj == 'undefined' ? 'HALL' : gameObj.id,
            //uid:userinfo ?　userinfo.id : 0,//这个不需要了，服务端存在secret一起了。 其实nickname也可以存，但是nickname并不敏感，不存也行。
            nickname:typeof userinfo == 'object' ? userinfo.nickname : '',//直接传给服务端，避免websocket读DB了。
            reconnect:that.reconnect
        };
        that.sendMsg(login_data);
        that.reconnect = '1';
    };

// 服务端发来消息时
    this.onmessage = function (e)
    {
        debug_log("on message:"+e.data);
        let data = JSON.parse(e.data);
        that.agentDistribute(data);
    };


    this.sendMsg = function(data){
        debug_log("do send 原始数据"+JSON.stringify(data));
        let string_data = '';
        switch (typeof data)
        {
            case 'string':
                string_data = data;
                break;
            case 'object':
                data['_token'] = ws_token['token'];
                data['_timestamp'] = ts_delta + Math.round(new Date().getTime()/1000);
                let full_data_obj = JSON.parse(JSON.stringify(data));//copy
                full_data_obj['_secret'] = ws_token['secret'];//secret 不会打包进数据
                data['_checksum'] = this.object_md5(full_data_obj);
                string_data = JSON.stringify(data);
                break;
            default:
                break;
        }
        debug_log("do send 最终发送"+string_data);
        ws.send(string_data);
    };

    //消息代理分发
    this.agentDistribute = function(data){
        if(typeof data.type != 'string' || data.type == '')
        {
            return;
        }
        let function_name = 'action'+data['type'].charAt(0).toUpperCase() + data['type'].slice(1);
        if(typeof that[function_name] == 'function')
        {
            return that[function_name](data);
        }
    };

    // 服务端ping客户端
    this.actionPing = function(_data){
        that.sendMsg({type:"pong"});
    };
    // 服务端ping客户端
    this.actionLogin = function(_data){
        let new_li = $(document.createElement("li"));
        $(document.createElement('span')).text(_data.user.nickname + pager.t(' has joined.')).appendTo(new_li);
        new_li.appendTo($("#chat_content"));
        //滚动。
        $("#chat_content_list").scrollTop($("#chat_content_list")[0].scrollHeight - $("#chat_content_list").height());
    };


    //进入房间之后会接收到自己的client_id 和一些历史消息
    this.actionEnter = function(_data)
    {
        global_current_client_id = _data['client_id'];
        if(_data!=null && _data['history_msg']!=null&&_data['history_msg'].length>0){
            for(let i in _data['history_msg']){
                that.agentDistribute(_data['history_msg'][i]);
            }
        }
    };


    this.actionClient_list = function(_data){
        pager.show_user_list(_data.client_list);
    };

    this.actionGame_info = function(_data){
        board.load(_data.game);
    };

    // 接收发言消息
    this.actionSay = function(_data){
        pager.show_msg(_data.content,_data.from_user,(typeof _data.board == 'string' ? _data.board : ''));
    };

    this.actionGames = function(_data)
    {
        pager.show_game_list(_data.games);
    };


    this.actionInvite = function(_data){
        pager.invite(_data.invite);
        pager.play_sound("Invitation");
    };

    this.actionGame_start = function(_data){
        let _href = "/game/" + _data.game_id;
        if(typeof board == 'object' && board != null &&  board.gameData.status == 1)
        {
            pager.show_msg('<span style="color: #3367d6;font-weight:bold;">您有一盘新对局，<a href="' +_href+ '" target="_blank">请点击查看</a></span>');
        }
        else{
            window.location.href = _href;
        }
    };

    // 用户退出 更新用户列表
    this.actionLogout = function(_data){
        debug_log(_data);
    };

    this.actionNotice = function(_data){
        pager.show_msg(_data.content);
    };
    this.actionGame_over = function(_data){
        pager.show_msg('<span style="color: #3367d6;font-weight:bold;">' +_data.content + '</span>');
        pager.play_sound("GameOver");
    };

    this.actionShutdown = function(_data)
    {
        //正常业务结束，服务器端要求关闭并且不再重连
        //if(typeof shutdownCallback == 'function') shutdownCallback(_data);
        ws.onclose = function () {
            return false;
        };
        global_current_client_id = '';
        ws.close();
    }
};

let  _chat={
    chatObj:null,
    getChat:function(){
        if(this.chatObj==null)this.chatObj = new chat();
        return this.chatObj;
    }
};
if(typeof ws_token !== "undefined")
{
    _chat.getChat().connect();
}

