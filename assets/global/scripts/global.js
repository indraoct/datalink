function clearForm(ele) {

        $(ele).find(':input').each(function() {
            switch(this.type) {
                case 'password':
                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
					$(this).select2("val",'');
                    $(this).val('');
                    break;
                case 'checkbox':
                case 'radio':
                    this.checked = false;
            }
        });

}

function Digits(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode == 44 || charCode == 45 || charCode == 46 || charCode == 8 || (charCode >= 48 && charCode <= 57)) return true;
	return false;
}

function displayNumeric(num,aSep,aDec)
{
	var minus = '';
	if (num === undefined || num === null)
		return '';
	else	
		num = num.toString();
	
	if(num.charAt(0)=='-')
		minus = '-';
	num = num.toString().replace(/\$|\,/g,'');
	// num = num.toString().replace('/\\'+aSep+'/g','');
	if(isNaN(num))
		num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+aSep+
	num.substring(num.length-(4*i+3));
	return (minus + num + aDec + cents);
}

function defaultNumeric(num,aSep,aDec)
{
	if(!num)
		num = "0";
	var regex1 = new RegExp('\\'+aSep,'g');
	var n = num.toString().replace(regex1,'');
	var regex2 = new RegExp('\\'+aDec,'g');
	num = n.replace(regex2,'.');
	if(isNaN(num))
		num = "0";
	return num;
}