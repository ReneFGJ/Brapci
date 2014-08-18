<style>
.calc1 {
	border : 1px solid Black;
	font-size : 22px;
	height : 30px;
	text-align : right;
	width : 200px;
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	background-color : #FFFAF0;
}
</style>
<body onkeypress="return onkey(this);" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<TABLE background="img/calc_bg.jpg" width="220" cellpadding="0" cellspacing="0">
<TR><TD colspan="3" align="right"><font style="font-size: 9px; color: #FFFFFF;  font-family: Verdana;">sisDOC - calc 0.0a&nbsp;&nbsp;</TD></TR>
<TR>
<TD width="10"><form id="calc" name="calc"></TD>
<TD width="*" align="center"><input type="text" name="rst" value="" size="20" maxlength="20" class="calc1">
<TD width="10"></form></TD>
</TD></TR>
<TR><TD><TD>
	<TABLE width="100%" cellpadding="0" cellspacing="0">
	<TR><TD colspan="4"><img src="img/nada_white.gif" width="5" height="5" alt="" border="0"></TD></TR>
	<TR align="center">
	<TD><img src="img/calc_m11.jpg" width="47" height="32" alt="" border="0"></TD>
	<TD><img src="img/calc_m12.jpg" width="47" height="32" alt="" border="0"></TD>
	<TD><img src="img/calc_m13.jpg" width="47" height="32" alt="" border="0"></TD>
	<TD><img src="img/calc_m14.jpg" width="47" height="32" alt="" border="0"></TD>
	</TR>
	<TR><TD colspan="4"><img src="img/nada_white.gif" width="5" height="5" alt="" border="0"></TD></TR>
	<TR align="center">
	<TD><img src="img/calc_m21.jpg" width="47" height="32" alt="" border="0" onclick="tecla('7');"></TD>
	<TD><img src="img/calc_m22.jpg" width="47" height="32" alt="" border="0" onclick="tecla('8');"></TD>
	<TD><img src="img/calc_m23.jpg" width="47" height="32" alt="" border="0" onclick="tecla('9');"></TD>
	<TD><img src="img/calc_m24.jpg" width="47" height="32" alt="" border="0" onclick="tecla('');"></TD>
	</TR>
	<TR><TD colspan="4"><img src="img/nada_white.gif" width="5" height="5" alt="" border="0"></TD></TR>
	<TR align="center">
	<TD><img src="img/calc_m31.jpg" width="47" height="32" alt="" border="0" onclick="tecla('4');"></TD>
	<TD><img src="img/calc_m32.jpg" width="47" height="32" alt="" border="0" onclick="tecla('5');"></TD>
	<TD><img src="img/calc_m33.jpg" width="47" height="32" alt="" border="0" onclick="tecla('6');"></TD>
	<TD><img src="img/calc_m34.jpg" width="47" height="32" alt="" border="0" onclick="tecla('');"></TD>
	</TR>
	<TR><TD colspan="4"><img src="img/nada_white.gif" width="5" height="5" alt="" border="0"></TD></TR>
	<TR align="center">
	<TD><img src="img/calc_m41.jpg" width="47" height="32" alt="" border="0" onclick="tecla('1');"></TD>
	<TD><img src="img/calc_m42.jpg" width="47" height="32" alt="" border="0" onclick="tecla('2');"></TD>
	<TD><img src="img/calc_m43.jpg" width="47" height="32" alt="" border="0" onclick="tecla('3');"></TD>
	<TD><img src="img/calc_m44.jpg" width="47" height="32" alt="" border="0" onclick="tecla('');"></TD>
	</TR>		
	<TR><TD colspan="4"><img src="img/nada_white.gif" width="5" height="5" alt="" border="0"></TD></TR>
	<TR align="center">
	<TD><img src="img/calc_m51.jpg" width="47" height="32" alt="" border="0" onclick="tecla('0');"></TD>
	<TD><img src="img/calc_m52.jpg" width="47" height="32" alt="" border="0" onclick="tecla('.');"></TD>
	<TD><img src="img/calc_m53.jpg" width="47" height="32" alt="" border="0" onclick="tecla('');"></TD>
	<TD><img src="img/calc_m54.jpg" width="47" height="32" alt="" border="0" onclick="tecla('');"></TD>
	</TR>		
	<TR><TD colspan="4">&nbsp;</TD></TR>
	</TABLE>
</TD><TD></TD>
<TR>
<TD width="10"><form id="rrr" name="rrr"></TD>
<TD width="*" align="center">
<input type="text" name="rst" value="" size="5" maxlength="20">
<input type="text" name="ope" value="" size="1" maxlength="1">
<TD width="10"></form></TD>

</TABLE>
<script>
	calc.rst.focus();
	function tecla(a)
		{
		calc.rst.value = calc.rst.value + a;
		}
	function onkey(a)
		{
		var e = event;
		var k = e.keyCode || e.which;
		var v = 0;
		if ((k >= 48) & (k <= 57)) { v = 1 }
		if (k == 44) { e.keyCode = 46; v = 1 }
		if (k == 46) { v = 1 } // PONTO
		
		///////// funcao
		if ((k==43) || (k==45) || (k==42) || (k==47) || (k==61))
		{
			vi = rrr.rst.value;
			vc = rrr.ope.value;
			if (vi == '') { vi = 0; }
			if (vc=='+') { rrr.rst.value = parseFloat(parseFloat(vi) + parseFloat(calc.rst.value)); calc.rst.value=''; }
			if (vc=='-') { rrr.rst.value = parseFloat(parseFloat(vi) - parseFloat(calc.rst.value)); calc.rst.value=''; }
			if (vc=='*') { rrr.rst.value = parseFloat(parseFloat(vi) * parseFloat(calc.rst.value)); calc.rst.value=''; }
			if (vc=='/') { rrr.rst.value = parseFloat(parseFloat(vi) / parseFloat(calc.rst.value)); calc.rst.value=''; }
			if (vc=='')  { rrr.rst.value =parseFloat(calc.rst.value); calc.rst.value=''; }
		}
		
		if (k == 43) { v = 0 ; rrr.ope.value = '+'; } // MAIS
		if (k == 45) { v = 0 ; rrr.ope.value = '-'; } // MENOS
		if (k == 42) { v = 0 ; rrr.ope.value = '*'; } // VEZES
		if (k == 47) { v = 0 ; rrr.ope.value = '/'; } // DIVIDIR
		if (k == 99) { v = 0; rrr.rst.value = ''; } // ZERAR
		if (k == 99 || k == 67) { v = 0; rrr.rst.value = ''; calc.rst.value = ''; } // ZERAR

		if (k == 61) /////////// IGUAL
			{ 
			v = 0 
			calc.rst.value = rrr.rst.value;
			rrr.ope.value = '';
			} 

		if (v==0)
			{ 
			// alert(k) 
			}
		if (v==0) { return false }
		//		a.focus();
//		a.select();
		return k;
		}
</script>