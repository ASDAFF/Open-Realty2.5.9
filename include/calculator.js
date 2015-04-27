
function PopUpPrint () {
	var input_string = "";
	if (CalQualAmt()) {
		input_string = "loanqualifier_calc_displaycertificate_frame.asp";
		input_string = input_string + "?IncOne=" +  document.getElementById('qualify').IncOne.value;
		input_string = input_string + "&IncTwo=" +  document.getElementById('qualify').IncTwo.value;
		input_string = input_string + "&OthInc=" +  document.getElementById('qualify').OthInc.value;
		input_string = input_string + "&RentInc=" +  document.getElementById('qualify').RentInc.value;
		input_string = input_string + "&income1=" +  document.getElementById('qualify').income1.options[ document.getElementById('qualify').income1.selectedIndex].text;
		input_string = input_string + "&income2=" +  document.getElementById('qualify').income2.options[ document.getElementById('qualify').income2.selectedIndex].text;
		input_string = input_string + "&incomeother=" +  document.getElementById('qualify').incomeother.options[ document.getElementById('qualify').incomeother.selectedIndex].text;
		input_string = input_string + "&incomerental=" +  document.getElementById('qualify').incomerental.options[ document.getElementById('qualify').incomerental.selectedIndex].text;
		input_string = input_string + "&Applicants=" + returnRadioValue( document.getElementById('qualify').Applicants);
		input_string = input_string + "&HomeLoan=" +  document.getElementById('qualify').HomeLoan.value;
		input_string = input_string + "&OthLoan=" +  document.getElementById('qualify').OthLoan.value;
		input_string = input_string + "&CardLim=" +  document.getElementById('qualify').CardLim.value;
		input_string = input_string + "&NumbDep=" +  document.getElementById('qualify').NumbDep.value;
		input_string = input_string + "&IntRate=" +  document.getElementById('qualify').IntRate.value;
		input_string = input_string + "&TermYear=" +  document.getElementById('qualify').TermYear.value;
		input_string = input_string + "&TermMonth=" +  document.getElementById('qualify').TermMonth.value;
		input_string = input_string + "&LoanAmt=" +  document.getElementById('qualify').LoanAmt.value;
		input_string = input_string + "&loanType=" + returnRadioValue( document.getElementById('qualify').loanType);

		window.open(input_string,'certificate','width=640,height=600,scrollbars=yes,resizable=yes,screenX=0,screenY=0,left=0,top=0');
	}
}


function returnRadioValue(sFieldName) {
	var cFields = sFieldName;

	for (x = 0; x < cFields.length; x++) {
		if (cFields[x].checked) {
			return cFields[x].value;
			break;
		}
	}

	return null;
}


function checkEnter(pfield, pname) {
    var field = pfield.value
    var msg
    var status = true

    if (field.length == 0) {
        msg = "The '" + pname + "' field must be entered."
        alert(msg)
        status = false
    }

    return status
}

function checkNumberofApplicants(pfield, pname) {
    var field = returnRadioValue(pfield)
    var msg
    var status = true

    if ((field == 2) && ( document.getElementById('qualify').IncTwo.value.length==0)) {
        msg = "You have entered Joint for the No. of Applicants. The Income 2 field must be entered."
        alert(msg)
        status = false
    }

    if ((field == 1) && (ParseDollar( document.getElementById('qualify').IncTwo.value)>0)) {
        msg = "You have entered Single for the No. of Applicants. The Income 2 field cannot be entered."
        alert(msg)
        status = false
    }

    return status
}


function checkLoanType(pfield) {
    var field = returnRadioValue(pfield)
    var msg
    var status = true

    if (field == "Home") {
		 document.getElementById('qualify').IntRate.value = "8.16"
		ACF=10 // Account Keeping Fee
		fixedInterestRate = false    //sets the intrest rate text box to a free form box
        status = false
    }

    if (field == "Personal") {
         document.getElementById('qualify').IntRate.value = "12.95"
		ACF=0 // Account Keeping Fee
		fixedInterestRate = false  //sets the intrest rate text box to a free form box
        status = false
    }

    return status
}


function checkIfFixInterestRateApplies() {
	if (fixedInterestRate == true) {  // remove focus
		 document.getElementById('qualify').TermYear.focus();
	}
}


function checkNumb(pnumb, pname) {
    var numb = pnumb.value
    var message
    var indx
    var status = false

    if (numb.length == 0) {
        status = true
    }

    for (var indx = 0; indx < numb.length; indx++) {  /* the field should contain at least one digit */
        if (numb.charAt(indx)>= "0" && numb.charAt(indx) <= "9") {
            status = true
        }
    }

    for (var indx = 0; indx < numb.length; indx++) {
        if (!((numb.charAt(indx)>= "0" && numb.charAt(indx) <= "9") ||
             numb.charAt(indx) == " " ||
             numb.charAt(indx) == "." ||
             numb.charAt(indx) == ",")) {
            status = false
        }
    }

    if (!status) {
        msg = "The '" + pname + "' field must be a number."
        alert(msg)
    }
    return status
}

function parseNumb(pnumbstr) {
    var numb = pnumbstr.value
    var indx

    if (numb.length> 0) {
        indx = numb.indexOf(",")
    } else {
        indx = -1
    }
    while (indx != -1) {
        numb = numb.substring(0,indx) + numb.substring(indx*1 + 1,numb.length)
        indx = numb.indexOf(",")
    }

    if (numb.length> 0) {
        indx = numb.indexOf(" ")
    } else {
        indx = -1
    }
    while (indx != -1) {
        numb = numb.substring(0,indx) + numb.substring(indx*1 + 1,numb.length)
        indx = numb.indexOf(" ")
    }
    if (numb == "") {
        numb = 0
    }
    return numb
}

function parseMonth(pyear, pmonth) {
    var year  = parseNumb(pyear)
    var month = parseNumb(pmonth)
    var period
    var indx

    period = Math.ceil(month*1 + year*12)

    return period
}

function ParseDollar(pNumber) {
    var money = ""
    var numb = Math.round(pNumber)
    var indx1
    var indx2 = 0

    numb = "" + numb
    indx1 = numb.length - 1

    while (indx1> -1) {
        indx2 += 1
        if (indx2 == 4) {
            money = "," + money
            indx2 = 1
        }
        money = numb.substring(indx1,indx1*1 + 1) + money
        indx1 -= 1
    }

     return money
}

function checkIncOne(pInc) {
    var Inc = pInc
    var status = false

    if (checkEnter(Inc,"First Income")) {
        status = checkNumb(Inc,"First Income")
    }

    return status
}


function checkIntRate(pIntRate) {
    var IntRate = pIntRate
    var status  = true

    if (checkEnter(IntRate,"Interest Rate")) {
        if (checkNumb(IntRate,"Interest Rate")) {
            if (IntRate.value == 0) {
                alert("The Interest Rate can not be zero.")
                status = false
            }
        } else {
            status = false
        }
    } else {
        status = false
    }

    return status
}

function checkTermY(pYear) {
    var TermYear = pYear
    var status   = true

    if (checkEnter(TermYear,"Year Period")) {
        if (checkNumb(TermYear,"Year Period")) {
            if (TermYear.value> 30) {
                alert("The Term of Loan can not be longer than 30 years.")
                status = false
            }
        } else {
            status = false
        }
    } else {
        status = false
    }

    return status
}

function checkTermM(pYear, pMonth) {
    var TermYear  = pYear
    var TermMonth = pMonth
    var status    = true
    var ValMonth  = pMonth.value

    if (ValMonth.length != 0) {
        if (checkNumb(TermMonth,"Month Period")) {
           if (TermMonth.value> 0 && TermYear.value> 29) {
                alert("The Term of Loan can not be longer than 30 years.")
                status = false
            }
            if (TermMonth.value> 11) {
                alert("Please use the Year field to enter a period longer than 11 months.")
                status = false
            }
        } else {
            status = false
        }
    }

    return status
}

var ACF = 10 //Account Keeping Fee
var fixedInterestRate = true //default interest rate type

function CalQualAmt() {
    var IncOne
    var IncTwo
    var OthInc
    var RentInc
    var TotInc

    var HomeLoan
    var OthLoan
    var CardLim
    var NumbDep
	var AmtDep
    var OthExp
    var TotExp

    var IntRate
    var MonthTerm
    var RepayAmt
    var QualAmt

    if (checkIncOne( document.getElementById('qualify').IncOne) &&
	  checkNumb( document.getElementById('qualify').IncTwo,"Second Income") &&
	  checkNumb( document.getElementById('qualify').OthInc,"Other Income") &&
	  checkNumb( document.getElementById('qualify').RentInc,"Rental Income") &&
	  checkNumberofApplicants( document.getElementById('qualify').Applicants, "No. of Applicants") &&
	  checkNumb( document.getElementById('qualify').HomeLoan,"Home Loan") &&
	  checkNumb( document.getElementById('qualify').OthLoan,"Other Loan") &&
	  checkNumb( document.getElementById('qualify').CardLim,"Card Limit") &&
	  checkNumb( document.getElementById('qualify').NumbDep,"Number of Dependants") &&
	  checkIntRate( document.getElementById('qualify').IntRate) &&
      checkTermY( document.getElementById('qualify').TermYear) &&
      checkTermM( document.getElementById('qualify').TermYear, document.getElementById('qualify').TermMonth)) {
        IncOne  = (parseNumb( document.getElementById('qualify').IncOne)/12)* document.getElementById('qualify').income1.options[ document.getElementById('qualify').income1.selectedIndex].value;
        IncTwo =  (parseNumb( document.getElementById('qualify').IncTwo)/12)* document.getElementById('qualify').income2.options[ document.getElementById('qualify').income2.selectedIndex].value;
        OthInc =  (parseNumb( document.getElementById('qualify').OthInc)/12)* document.getElementById('qualify').incomeother.options[ document.getElementById('qualify').incomeother.selectedIndex].value;
        RentInc = (parseNumb( document.getElementById('qualify').RentInc)/12)* document.getElementById('qualify').incomerental.options[ document.getElementById('qualify').incomerental.selectedIndex].value;
        HomeLoan = parseNumb( document.getElementById('qualify').HomeLoan);
        OthLoan = parseNumb( document.getElementById('qualify').OthLoan);
        CardLim = parseNumb( document.getElementById('qualify').CardLim);
        NumbDep = parseNumb( document.getElementById('qualify').NumbDep);

        IntRate = parseNumb( document.getElementById('qualify').IntRate)/1200
        MonthTerm = parseMonth( document.getElementById('qualify').TermYear, document.getElementById('qualify').TermMonth)

		// if SINGLE app, make sure only one income box is filled in.
		if ((returnRadioValue( document.getElementById('qualify').Applicants)==1) && (IncTwo> 0)) {
			IncTwo = 0;
			 document.getElementById('qualify').IncTwo.value = 0;
			alert ("You have selected Single Application and entered a value for 'Income Two'. This value has now been reset to 0. If you require a Joint Income, please select it under 'Application Type'");
		}

        TotInc = IncOne + IncTwo + OthInc

		OthExp = TotInc * 0.35;

		if (OthExp < 1000 && returnRadioValue( document.getElementById('qualify').Applicants)==1) { //IncTwo == 0)
			OthExp = 1000;
		} else if (OthExp < 1300 && returnRadioValue( document.getElementById('qualify').Applicants)==2) {
			OthExp = 1300;
		} else if (OthExp> 2000 && returnRadioValue( document.getElementById('qualify').Applicants)==1) {
			OthExp = 2000;
		} else if (OthExp> 2600 && returnRadioValue( document.getElementById('qualify').Applicants)==2) {
			OthExp = 2600;
		}

		if (NumbDep> 0)
			AmtDep = 175 + (NumbDep - 1) * 125;
		else
			AmtDep = 0;

		TotExp = AmtDep*1 + HomeLoan*1 + OthLoan*1 + (CardLim * 0.03) + OthExp*1;

		RepayAmt = TotInc + (RentInc * 0.7) - TotExp

		// no ACF on personal loans
		LoanAmt = (RepayAmt - ACF) * (1 - Math.pow((1 + IntRate*1),-MonthTerm))/IntRate

		if (LoanAmt> 0) {
             document.getElementById('qualify').LoanAmt.value = ParseDollar(Math.round(LoanAmt))
			return true;
        } else {
			 document.getElementById('qualify').LoanAmt.value = "0"
			return true;
		}
    } else {
		 document.getElementById('qualify').LoanAmt.value = " "
		return false;
    }
}

function Init_Page() {
	 document.getElementById('qualify').IncOne.focus();
	 document.getElementById('qualify').IncOne.select();
}

// Calculate loan, open new window, submit form.
function PopUpPrint () {
	CalcRepay();
	inputstring = "repayment_calc_displaycertificate_frame.asp"
	inputstring = inputstring + "?LoanAmt1=" +  document.getElementById('repay').LoanAmt1.value
	inputstring = inputstring + "&IntRate1=" +  document.getElementById('repay').IntRate1.value
	inputstring = inputstring + "&TermYear1=" +  document.getElementById('repay').TermYear1.value
	inputstring = inputstring + "&TermMonth1=" +  document.getElementById('repay').TermMonth1.value
	inputstring = inputstring + "&RepayAmt1=" +  document.getElementById('repay').RepayAmt1.value
	window.open(inputstring,'certificate','width=640,height=600,scrollbars=yes,resizable=yes,screenX=0,screenY=0,left=0,top=0')
}

function checkEnter(pfield, pname) {
	var field = pfield.value
	var msg
	var status = true

	if (field.length == 0) {
		msg = "The " + pname + " field must be entered."
		alert(msg)
		status = false
	}

	return status
}

function checkNumb(pnumb, pname) {
	var numb = pnumb.value
	var message
	var indx
	var status = false

	for (var indx = 0; indx < numb.length; indx++) { /* the field should contain at least one digit */
		if (numb.charAt(indx)>= "0" && numb.charAt(indx) <= "9") {
			status = true
		}
	}
	for (var indx = 0; indx < numb.length; indx++) {
		if (!((numb.charAt(indx)>= "0" && numb.charAt(indx) <= "9") ||
		  numb.charAt(indx) == " " ||
		  numb.charAt(indx) == "." ||
		  numb.charAt(indx) == ",")) {
			status = false
		}
	}
	if (!status) {
		msg = "The " + pname + " field must be a number."
		alert(msg)
	}

	return status
}

function parseNumb(pnumbstr) {
	var numb = pnumbstr.value
	var indx

	indx = numb.indexOf(",")
	while (indx != -1) {
		numb = numb.substring(0,indx) + numb.substring(indx*1 + 1,numb.length)
		indx = numb.indexOf(",")
	}

	indx = numb.indexOf(" ")
	while (indx != -1) {
		numb = numb.substring(0,indx) + numb.substring(indx*1 + 1,numb.length)
		indx = numb.indexOf(" ")
	}

	return numb
}

function parseMonth(pyear, pmonth) {
	var year  = parseNumb(pyear)
	var month = parseNumb(pmonth)
	var period
	var indx

	period = Math.ceil(month*1 + year*12)

	return period
}

function checkLoan(pLoan) {
	var LoanAmt = pLoan
	var status

	if (checkEnter(LoanAmt,"Loan Amount")) {
		status = checkNumb(LoanAmt,"Loan Amount")
	}

	return status
}

function checkRepay(pRepay) {
	var RepayAmt = pRepay
	var status   = true

	if (checkEnter(RepayAmt,"Repay Amount")) {
		if (checkNumb(RepayAmt,"Repay Amount")) {
			if (RepayAmt.value == 0) {
				alert("The Repay Amount can not be zero.")
				status = false
			}
		} else {
			status = false
		}
	} else {
		status = false
	}

	return status
}

function checkIntRate(pIntRate) {
	var IntRate = pIntRate
	var status  = true

	if (checkEnter(IntRate,"Interest Rate")) {
		if (checkNumb(IntRate,"Interest Rate")) {
			if (IntRate.value == 0) {
				alert("The Interest Rate can not be zero.")
				status = false
			}
		} else {
			status = false
		}
	} else {
		status = false
	}

	return status
}

function checkTermY(pYear) {
	var TermYear = pYear
	var status   = true

	if (checkEnter(TermYear,"Year Period")) {
		if (checkNumb(TermYear,"Year Period")) {
			if (TermYear.value> 30) {
				alert("The Year Period can not be longer than 30 years.")
				status = false
			}
		} else {
			status = false
		}
	} else {
		status = false
	}

	return status
}

function checkTermM(pMonth) {
	var TermMonth = pMonth
	var status    = true

	if (checkEnter(TermMonth,"Month Period")) {
		if (checkNumb(TermMonth,"Month Period")) {
			//if (TermMonth.value> 11) {
				//alert("Please use the Year field to enter a period longer than 11 months.")
				//status = false
			//}
		} else {
			status = false
		}
	} else {
		status = false
	}

	return status
}

function CalcRepayAmt(pLoanAmt, pIntRate, pTerm) {
	var RepayAmt

	RepayAmt = pLoanAmt * pIntRate/(1 - Math.pow((1 + pIntRate*1),-pTerm))
	RepayAmt = Math.ceil(RepayAmt)

	return RepayAmt
}

/////////////////////////////////////////////////////////
function CalcRepay() {
	var LoanAmount
	var InterestRate
	var MonthTerm

	if (checkLoan( document.getElementById('repay').LoanAmt1) &&
	  checkIntRate( document.getElementById('repay').IntRate1) &&
	  checkTermY( document.getElementById('repay').TermYear1) &&
	  checkTermM( document.getElementById('repay').TermMonth1)) {
		LoanAmount   = parseNumb( document.getElementById('repay').LoanAmt1)
		InterestRate = parseNumb( document.getElementById('repay').IntRate1)/1200
		MonthTerm    = parseMonth( document.getElementById('repay').TermYear1, document.getElementById('repay').TermMonth1)

		 document.getElementById('repay').RepayAmt1.value = CalcRepayAmt(LoanAmount, InterestRate, MonthTerm)
	} else {
		 document.getElementById('repay').RepayAmt1.value = " "
	}
}
/////////////////////////////////////////////////////
function CalcTerm() {
	/* Calculate the Repaid Period */

	var MonthTerm    = 0
	var YearTerm     = 0
	var MaxTerm      = 30 * 12     /* 30 years */
	var LoanAmount
	var RepayAmount
	var InterestRate
	var RepayFreq
	var MinRepay

	if (checkLoan( document.getElementById('repay').LoanAmt2) &&
	  checkRepay( document.getElementById('repay').RepayAmt2) &&
	  checkIntRate( document.getElementById('repay').IntRate2)) {
		LoanAmount   = parseNumb( document.getElementById('repay').LoanAmt2)
		RepayAmount  = parseNumb( document.getElementById('repay').RepayAmt2)
		InterestRate = parseNumb( document.getElementById('repay').IntRate2)/1200
		MinRepay     = CalcRepayAmt(LoanAmount, InterestRate, MaxTerm)
		RepayFreq = document.getElementById('repay').RepayFreq.options[document.getElementById('repay').RepayFreq.selectedIndex].value
		RepayAmount = RepayAmount * RepayFreq/12
		if (RepayAmount < MinRepay) {
			Msg = "Sorry, the minimum repayment amount must be " + MinRepay + " dollars."
			alert(Msg)
			 document.getElementById('repay').TermYear2.value  = " "
			 document.getElementById('repay').TermMonth2.value = " "
		} else {
			MonthTerm = Math.log(RepayAmount/(RepayAmount - LoanAmount * InterestRate)) / Math.log(1 + InterestRate)
			MonthTerm = Math.ceil(MonthTerm)
			YearTerm = Math.floor(MonthTerm / 12)
			MonthTerm = MonthTerm - (YearTerm * 12)

			 document.getElementById('repay').TermYear2.value  = YearTerm
			 document.getElementById('repay').TermMonth2.value = MonthTerm
		}
	} else {
		 document.getElementById('repay').TermYear2.value  = " "
		 document.getElementById('repay').TermMonth2.value = " "
	}
}
/////////////////////////////////////////////////////////
function CalcLoan() {
	var LoanAmt
	var RepayAmount
	var InterestRate
	var MonthTerm

	if (checkRepay( document.getElementById('repay').RepayAmt3) &&
	  checkIntRate( document.getElementById('repay').IntRate3) &&
	  checkTermY( document.getElementById('repay').TermYear3) &&
	  checkTermM( document.getElementById('repay').TermMonth3)) {
		RepayAmount  = parseNumb( document.getElementById('repay').RepayAmt3)
		InterestRate = parseNumb( document.getElementById('repay').IntRate3)/1200
		MonthTerm    = parseMonth( document.getElementById('repay').TermYear3, document.getElementById('repay').TermMonth3)
		LoanAmt = RepayAmount * (1 - Math.pow((1 + InterestRate*1),-MonthTerm))/InterestRate

		 document.getElementById('repay').LoanAmt3.value = Math.floor(LoanAmt)
	} else {
		 document.getElementById('repay').LoanAmt3.value = " "
	}
}

