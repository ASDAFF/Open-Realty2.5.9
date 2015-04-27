<?php
/**
 * Open-Realty
 *
 * Open-Realty is free software; you can redistribute it and/or modify
 * it under the terms of the Open-Realty License as published by
 * Transparent Technologies; either version 1 of the License, or
 * (at your option) any later version.
 *
 * Open-Realty is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * Open-Realty License for more details.
 * http://www.open-realty.org/license_info.html
 *
 * You should have received a copy of the Open-Realty License
 * along with Open-Realty; if not, write to Transparent Technologies
 * RR1 Box 162C, Kingsley, PA  18826  USA
 *
 * @author Ryan C. Bonham <ryan@transparent-tech.com>
 * @copyright Transparent Technologies 2004, 2005
 * @link http://www.open-realty.org Open-Realty Project
 * @link http://www.transparent-tech.com Transparent Technologies
 * @link http://www.open-realty.org/license_info.html Open-Realty License
 */

/**
 * calculators
 * Contains al functions needed to display the calculators.
 *
 * @author Ryan Bonham
 * @copyright Copyright (c) 2005
 */
class calculators {
	/**
	 * calculators::writemenu()
	 * Shows the menu of calculator choises.
	 *
	 * @return string Returns a html table with links for each calculator type.
	 */
	function writemenu()
	{
		global $lang;
		if (isset($_GET['price'])) {
			$_GET['price']=intval($_GET['price']);
			$display = '<table width="100%" border="0"><tr><td align="center"><h2>' . $lang['calc_page_header'] . '</h2><a href="index.php?action=calculator&amp;show=LoanTerm&amp;popup=yes&amp;price=' . $_GET['price'] . '">' . $lang['calc_menu_Loan_Term'] . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?action=calculator&amp;show=LoanQualifier&amp;popup=yes&amp;price=' . $_GET['price'] . '">' . $lang['calc_menu_Loan_Qualifier'] . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?action=calculator&amp;show=LoanRepayment&amp;popup=yes&amp;price=' . $_GET['price'] . '">' . $lang['calc_menu_Loan_Repayment'] . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?action=calculator&amp;show=LoanAmount&amp;popup=yes&amp;price=' . $_GET['price'] . '">' . $lang['calc_menu_Loan_Amount'] . '</a></td></tr></table>';
		}else {
			$display = '<table width="100%" border="0"><tr><td align="center"><h2>' . $lang['calc_page_header'] . '</h2><a href="index.php?action=calculator&amp;show=LoanTerm&amp;popup=yes">' . $lang['calc_menu_Loan_Term'] . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?action=calculator&amp;show=LoanQualifier&amp;popup=yes">' . $lang['calc_menu_Loan_Qualifier'] . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?action=calculator&amp;show=LoanRepayment&amp;popup=yes">' . $lang['calc_menu_Loan_Repayment'] . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?action=calculator&amp;show=LoanAmount&amp;popup=yes">' . $lang['calc_menu_Loan_Amount'] . '</a></td></tr></table>';
		}
		return $display;
	} //End function writemenu()
	/**
	 * calculators::start_calc()
	 * This is the function which displays the calculator.
	 *
	 * @return string Returns the html to dispaly the calculators
	 */
	function start_calc()
	{
		global $conn, $config, $lang, $show;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// required to show featured listings
		require_once($config['basepath'] . '/include/listing.inc.php');
		$listings = new listing_pages();
		$price = '100000';
		if (isset($_GET['price'])) {
			$price = intval($_GET['price']);
		}

		$display = '<!-- Call the jscript to operate the calcualtors -->';
		$display .= '<script src="' . $config['baseurl'] . '/include/calculator.js" type="text/javascript"></script>';
		$display .= '<table class="page_display">';
		$display .= '<tr>';
		$display .= '<!--only align the content to the top allow use of the admin area to set this as wanted that way -->';
		$display .= '<td class="page_display">';
		// write menu here allways visable
		$display .= calculators::writemenu();
		// If $_GET['show'] isnt set make it blank.
		if (!isset($_GET['show'])) {
			$_GET['show'] = '';
		}
		if ($_GET['show'] == '') {
			$display .= '<table  width="80%" border="0" cellspacing="2" cellpadding="2">';
			$display .= '<tr>';
			$display .= '<td> <p>&nbsp;</p>';
			$display .= '<p>' . $lang['calc_intro_para_one'] . '</p>';
			$display .= '<p>' . $lang['calc_intro_para_two'] . '</p>';
			$display .= '</td></tr></table>';
		}
		// ==================== LOAN TERM ============================== #
		if ($_GET['show'] == 'LoanTerm') {
			$display .= '<table width="80%" border="0" cellspacing="2" cellpadding="2">';
			$display .= '<tr>';
			$display .= '<td>';
			$display .= '<h1>' . $lang['calc_Loan_Term_header'] . '</h1>';
			$display .= '<p>' . $lang['calc_Loan_Term_intro'] . '</p>';
			$display .= '<form id="repay" method="post" action="#">';
			$display .= '<table cellspacing="0" cellpadding="2" border="0">';
			$display .= '<tr>';
			$display .= '<td valign="top">' . $lang['calc_term_Loan_amount'] . '</td>';
			$display .= '<td valign="top" rowspan="3" style="height:1px;"></td>';
			$display .= '<td valign="top"><input onblur="checkLoan(LoanAmt2);" size="12" value="' . $price . '" name="LoanAmt2" /> [' . $config['money_sign'] . ']</td></tr>';
			$display .= '<tr>';
			$display .= '<td valign="top">' . $lang['calc_term_Regular_repayment_amount'] . '</td>';
			$display .= '<td valign="top"><input onblur="checkRepay(RepayAmt2);" size="12" value="700" name="RepayAmt2" /> <select name="RepayFreq"> <option value="12" selected="selected">' . $lang['calc_term_per_month'] . '</option> <option value="26">' . $lang['calc_term_per_biweekly'] . '</option> ';
			$display .= '<option value="52">' . $lang['calc_term_per_week'] . '</option></select> </td></tr>';
			$display .= '<tr>';
			$display .= '<td valign="top">' . $lang['calc_term_Annual_interest_rate'] . '</td>';
			$display .= '<td valign="top"><input size="12" value="7.00" name="IntRate2" onblur="checkIntRate(IntRate2);" /> ';
			$display .= '[%]</td></tr></table>';
			$display .= '<table cellspacing="2" cellpadding="2" width="100%" border="0">';
			$display .= '<tr>';
			$display .= '<td valign="baseline" align="right"><input onclick="CalcTerm();" type="button" value="' . $lang['calc_button_Calculate'] . '" name="Calculate" /></td></tr>';
			$display .= '<tr>';
			$display .= '<td valign="top" align="right"><b>' . $lang['calc_term_It_will_take'] . ' <input size="5"  name="TermYear2" /> ' . $lang['calc_term_years_and'] . ' <input size="5" name="TermMonth2" />';
			$display .= $lang['calc__term_months_to_repay'] . '.</b></td></tr></table>';
			$display .= '</form><br /><br />';
			$display .= '</td> ';
			$display .= '</tr> ';
			$display .= '</table>';
		} //End
		// ================== Loan Qualifier ================================ #
		if ($_GET['show'] == 'LoanQualifier') {
			$display .= '<table width="80%" border="0" cellspacing="2" cellpadding="2">
					<tr>
					<td>
					<h1>' . $lang['calc_Qual_header'] . '</h1>
					<p></p>
					<p>' . $lang['calc_Qual_intro_para_one'] . '</p>
					<p>' . $lang['calc_Qual_intro_para_two'] . '</p>
					<form id="qualify" method="post" action="#">
					<table cellspacing="0" cellpadding="0" width="100%" border="0">

					<tr>
					<td valign="top">' . $lang['calc_Qual_Application_Type'] . ' </td>
					<td valign="top"><input type="radio" checked="checked" value="1"
					name="Applicants" /> ' . $lang['calc_Qual_Single'] . ' <input type="radio" value="2"
					name="Applicants" /> ' . $lang['calc_Qual_Joint'] . ' </td></tr>
					<tr>
					<td valign="top">' . $lang['calc_Qual_Loan_Type'] . ' </td>
					<td valign="baseline"><input
					onclick="checkLoanType(loanType);" type="radio" checked="checked"
					value="Home" name="loanType" />' . $lang['calc_Qual_Home_Investment_Loan'] . '<br /><input
					onclick="checkLoanType(loanType);" type="radio"
					value="Personal" name="loanType" />' . $lang['calc_Qual_Personal_Loan'] . ' </td></tr>
					<tr>
					<td colspan="2"><b>' . $lang['calc_Qual_Income'] . '</b> </td></tr>
					<tr>
					<td valign="top">' . $lang['calc_Qual_Enter_income_1_net_after_tax'] . ' </td>
					<td valign="top">
					<input size="12" name="IncOne" />
					<select name="income1">
					<option value="1" selected="selected">' . $lang['calc_Qual_per_year'] . '</option>
					<option value="12">' . $lang['calc_Qual_per_month'] . '</option>
					<option value="26">' . $lang['calc_Qual_per_fortnight'] . '</option>
					<option  value="52">' . $lang['calc_Qual_per_week'] . '</option>
					</select>
					</td></tr><tr>
					<td valign="top">' . $lang['calc_Qual_Enter_income_2_net_after_tax'] . '</td>
					<td valign="top">
					<input size="12" value="0" name="IncTwo" onclick="select()" />
					<select name="income2">
					<option value="1" selected="selected">' . $lang['calc_Qual_per_year'] . '</option>
					<option value="12">' . $lang['calc_Qual_per_month'] . '</option>
					<option value="26">' . $lang['calc_Qual_per_fortnight'] . '</option>
					<option value="52">' . $lang['calc_Qual_per_week'] . '</option>
					</select>
					</td></tr>
					<tr>
					<td valign="top">' . $lang['calc_Qual_Enter_other_income_net_after_tax'] . '</td>
					<td valign="top">
					<input size="12" value="0" name="OthInc" onclick="select()" />
					<select name="incomeother">
					<option value="1" selected="selected">' . $lang['calc_Qual_per_year'] . '</option>
					<option value="12">' . $lang['calc_Qual_per_month'] . '</option>
					<option value="26">' . $lang['calc_Qual_per_fortnight'] . '</option>
					<option value="52">' . $lang['calc_Qual_per_week'] . '</option>
					</select>
					</td></tr><tr>
					<td valign="top">' . $lang['calc_Qual_Enter_rental_investment_income'] . ' </td>
					<td valign="top"><input size="12" value="0" name="RentInc" onclick="select()" />
					<select name="incomerental">
					<option value="1" selected="selected">' . $lang['calc_Qual_per_year'] . '</option>
					<option value="12">' . $lang['calc_Qual_per_month'] . '</option>
					<option value="26">' . $lang['calc_Qual_per_fortnight'] . '</option>
					<option value="52">' . $lang['calc_Qual_per_week'] . '</option>
					</select>
					</td></tr><tr>
					<td colspan="2"><br /><b>' . $lang['calc_Qual_Expenses'] . '</b>
					</td></tr><tr>
					<td valign="top">' . $lang['calc_Qual_Enter_investment_loan_repayment'] . '</td>
					<td valign="top"><input size="12" value="0" name="HomeLoan" onclick="select()" /> [' . $lang['calc_Qual_per_month'] . ']
					 </td></tr><tr>
					<td valign="top">' . $lang['calc_Qual_Enter_other_loan_repayments'] . '
					</td>
					<td valign="top"><input size="12" value="0" name="OthLoan" onclick="select()" /> [' . $lang['calc_Qual_per_month'] . ']
					</td></tr><tr>
					<td valign="top">' . $lang['calc_Qual_Enter_your_total_credit_card_limit'] . '</td>
					<td valign="top"><input size="12" value="0" name="CardLim" onclick="select()" /> [' . $config['money_sign'] . '] </td></tr>
					<tr><td valign="top">' . $lang['calc_Qual_Enter_number_of_dependants'] . '
					</td>
					<td valign="top"><input size="12" value="0" name="NumbDep" onclick="select()" /> </td></tr>
					<tr>
					<td colspan="2"><i>
					' . $lang['calc_Qual_expense_note'] . '</i>
					</td></tr><tr>
					<td colspan="2"><br /><b>' . $lang['calc_Qual_Loan_Details'] . '</b> </td></tr>
					<tr>
					<td valign="top">' . $lang['calc_Qual_Qualification_Interest_Rate'] . ' </td>
					<td valign="baseline">
					<input size="6" value="8.16" name="IntRate" /> [' . $lang['calc_percentage_symbol'] . ']
					</td></tr><tr><td valign="top">' . $lang['calc_Qual_Enter_term_of_loan'] . ' </td>
					<td valign="top">
					<table cellspacing="0" cellpadding="0"><tr><td>
					<input size="6" value="25" name="TermYear" /></td>
					<td>
					<input size="2" name="TermMonth" />
					</td></tr><tr><td>[' . $lang['calc_Qual_Years'] . ']</td><td>[' . $lang['calc_Qual_Months'] . ']</td>
												</tr></table></td></tr></table>
					<table cellspacing="2" cellpadding="2" width="100%" border="0">

										<tr>
					<td valign="baseline" align="right"><input onclick="CalQualAmt();" type="button" value="' . $lang['calc_button_Calculate'] . '" name="Calculate" />
					</td></tr><tr>
					<td valign="top" align="right"><b>
					' . $lang['calc_Qual_The_amount_you_can_borrow_will_be_approximately'] . '
					<input value="0" name="LoanAmt" /></b> </td></tr></table></form><br /><br />
					  </td>
					  </tr>
					</table>
				';
		} //End
		if ($_GET['show'] == 'LoanRepayment') {
			$display .= '
					<table width="80%" border="0" cellspacing="2" cellpadding="2">
					<tr>
					<td>
					<h1>' . $lang['calc_repay_header'] . '</h1>
					<p></p>
					<p>' . $lang['calc_repay_intro'] . '</p>
					<form id="repay" method="post" action="#">
					<table cellspacing="0" cellpadding="2" border="0">
					<tr>
					<td valign="top">' . $lang['calc_repay_Loan_amount'] . '</td>
					<td valign="top" rowspan="4" style="height:1px;"></td>
					<td valign="top" colspan="2"><input
					onblur="checkLoan(LoanAmt1);" size="13" value="' . $price . '"
					name="LoanAmt1" /> [' . $config['money_sign'] . ']</td></tr>
					<tr>
					<td valign="top">' . $lang['calc_repay_Annual_interest_rate'] . '</td>
					<td valign="top" colspan="2"><input
					onblur="checkIntRate(IntRate1);" size="5" value="7.00"
					name="IntRate1" /> [' . $lang['calc_percentage_symbol'] . ']</td></tr>
					<tr>
					<td valign="top">' . $lang['calc_repay_Loan_term'] . '</td>
					<td valign="top"><input onblur="checkTermY(TermYear1);"
					size="3" value="25" name="TermYear1" /></td>
					<td valign="top"><input onblur="checkTermM(TermMonth1);"
					size="3" value="0" name="TermMonth1" /></td></tr>
					<tr>
					<td valign="top"></td>
					<td valign="baseline">[' . $lang['calc_Qual_Years'] . ']</td>
					<td valign="baseline">[' . $lang['calc_Qual_Months'] . ']</td></tr></table>
					<table cellspacing="2" cellpadding="2" width="100%" border="0">
					<tr>
					<td valign="baseline" align="right"><input onclick="CalcRepay();" type="button" value="' . $lang['calc_button_Calculate'] . '" name="Calculate" /></td></tr>
					<tr>
					<td valign="top" align="right"><b>' . $lang['calc_repay_Your_repayment_will_be_approximately'] . ' ' . $config['money_sign'] . ' <input size="10" value="0"
					name="RepayAmt1" /> ' . $lang['calc_Qual_per_month'] . '</b></td></tr></table>
					</form><br /><br />
					</td>
					</tr>
					</table>
				';
		} //End
		if ($_GET['show'] == 'LoanAmount') {
			$display .= '
					<table width="80%" border="0" cellspacing="2" cellpadding="2">
					<tr>
					<td>
					<h1>' . $lang['calc_amount_header'] . '</h1>
					<p></p>
					<p>' . $lang['calc_amount_intro'] . '</p>
					<form id="repay" method="post" action="#">
					<table cellspacing="0" cellpadding="1" border="0">
					<tr>
					<td valign="top">' . $lang['calc_amount_Regular_repayment_amount'] . '</td>
					<td valign="top" rowspan="4" style="height:1px;"></td>
					<td valign="top" colspan="2"><input
					onblur="checkRepay(RepayAmt3)" size="12" value="500"
					name="RepayAmt3" />' . $lang['calc_Qual_per_month'] . '</td></tr>
					<tr>
					<td valign="top">' . $lang['calc_amount_Annual_interest_rate'] . '</td>
					<td valign="top" colspan="2"><input
					onblur="checkIntRate(IntRate3)" size="5" value="7.00"
					name="IntRate3" /> [' . $lang['calc_percentage_symbol'] . ']</td></tr>
					<tr>
					<td valign="top">' . $lang['calc_amount_Loan_term'] . '</td>
					<td valign="top"><input onblur="checkTermY(TermYear3)"
					size="3" value="25" name="TermYear3" /></td>
					<td valign="top"><input onblur="checkTermM(TermMonth3)"
					size="3" value="0" name="TermMonth3" /></td></tr>
					<tr>
					<td valign="top"></td>
					<td valign="baseline">[' . $lang['calc_Qual_Years'] . ']</td>
					<td valign="baseline">[' . $lang['calc_Qual_Months'] . ']</td></tr></table>
					<table cellspacing="2" cellpadding="2" width="100%" border="0">
					<tr>
					<td valign="baseline" align="right"><input onclick="CalcLoan();" type="button" value="' . $lang['calc_button_Calculate'] . '" name="Calculate" /></td></tr>
					<tr>
					<td valign="top" align="right"><b>' . $lang['calc_amount_The_total_loan_amount_will_be'] . ' ' . $config['money_sign'] . ' <input size="10"
					name="LoanAmt3" /></b></td></tr></table></form>
					<br /><br />
					</td>
					</tr>
					</table>
				';
		} //End
		$display .= '<p style="font-size:10px;text-align: center;">' . $lang['calc_footer text'] . '</p>
				<!--Close the table colom this must remain! -->
				</td>
				<!--Close the table down. -->
				</tr>
				</table>
  				<!--Template closer -->';
		return $display;
	} // End page_display()
} //End page_display Class

?>