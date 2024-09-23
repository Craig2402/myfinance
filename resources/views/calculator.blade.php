<!-- resources/views/calculator.blade.php -->

<!-- Popup container for the calculator -->
<div id="calculator-popup" class="ui-widget-content" style="display: none; width: 300px; height: auto; position: absolute; top: 0; left: 0; background-color: #f0f0f0; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 10px; resize: both; overflow: hidden;">
    <div id="calculator-header" style="cursor: move; background-color: #333; color: white; padding: 10px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
        <span style="font-weight: bold;">Calculator</span>
        <button id="close-calculator" style="float: right; background: none; color: white; border: none; font-size: 18px; cursor: pointer;">×</button>
    </div>

    <div class="calculator" style="padding: 15px; max-height: calc(100% - 40px); overflow-y: auto;">
        <div class="output" style="background-color: #fff; border: 1px solid #ddd; border-radius: 5px; padding: 10px; margin-bottom: 10px; text-align: right; height: 100px; display: flex; flex-direction: column; justify-content: space-between;">
            <div class="previous-operand" style="font-size: 18px; color: #777; min-height: 27px;"></div>
            <div class="current-operand" style="font-size: 24px; font-weight: bold;"></div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
            <button data-all-clear style="grid-column: span 2; background-color: #ff4136; color: white;">AC</button>
            <button data-delete style="background-color: #ff851b; color: white;">DEL</button>
            <button data-operation="/" style="background-color: #0074d9; color: white;">÷</button>
            <button data-number style="background-color: #f0f0f0;">7</button>
            <button data-number style="background-color: #f0f0f0;">8</button>
            <button data-number style="background-color: #f0f0f0;">9</button>
            <button data-operation style="background-color: #0074d9; color: white;">*</button>
            <button data-number style="background-color: #f0f0f0;">4</button>
            <button data-number style="background-color: #f0f0f0;">5</button>
            <button data-number style="background-color: #f0f0f0;">6</button>
            <button data-operation style="background-color: #0074d9; color: white;">-</button>
            <button data-number style="background-color: #f0f0f0;">1</button>
            <button data-number style="background-color: #f0f0f0;">2</button>
            <button data-number style="background-color: #f0f0f0;">3</button>
            <button data-operation style="background-color: #0074d9; color: white;">+</button>
            <button data-number style="background-color: #f0f0f0; grid-column: span 2;">0</button>
            <button data-number style="background-color: #f0f0f0;">.</button>
            <button data-equals style="background-color: #2ecc40; color: white;">=</button>
        </div>
    </div>
</div>

<script>
    $(function() {
        $("#calculator-popup").draggable({
            handle: "#calculator-header"
        }).resizable();

        $("#close-calculator").on("click", function() {
            $("#calculator-popup").hide();
        });

        // Calculator functionality
        const calculator = {
            displayValue: '0',
            firstOperand: null,
            waitingForSecondOperand: false,
            operator: null,
        };

        function updateDisplay() {
            $('.current-operand').text(calculator.displayValue);
            if (calculator.firstOperand !== null && calculator.operator) {
                $('.previous-operand').text(`${calculator.firstOperand} ${calculator.operator} ${calculator.waitingForSecondOperand ? '' : calculator.displayValue}`);
            } else {
                $('.previous-operand').text('');
            }
        }

        function inputDigit(digit) {
            const { displayValue, waitingForSecondOperand } = calculator;

            if (waitingForSecondOperand === true) {
                calculator.displayValue = digit;
                calculator.waitingForSecondOperand = false;
            } else {
                calculator.displayValue = displayValue === '0' ? digit : displayValue + digit;
            }
        }

        function inputDecimal(dot) {
            if (calculator.waitingForSecondOperand === true) {
                calculator.displayValue = "0.";
                calculator.waitingForSecondOperand = false;
                return;
            }

            if (!calculator.displayValue.includes(dot)) {
                calculator.displayValue += dot;
            }
        }

        function handleOperator(nextOperator) {
            const { firstOperand, displayValue, operator } = calculator;
            const inputValue = parseFloat(displayValue);

            if (operator && calculator.waitingForSecondOperand) {
                calculator.operator = nextOperator;
                return;
            }

            if (firstOperand == null && !isNaN(inputValue)) {
                calculator.firstOperand = inputValue;
            } else if (operator) {
                const result = calculate(firstOperand, inputValue, operator);

                calculator.displayValue = `${parseFloat(result.toFixed(7))}`;
                calculator.firstOperand = result;
            }

            calculator.waitingForSecondOperand = true;
            calculator.operator = nextOperator;
        }

        function calculate(firstOperand, secondOperand, operator) {
            if (operator === '+') {
                return firstOperand + secondOperand;
            } else if (operator === '-') {
                return firstOperand - secondOperand;
            } else if (operator === '*') {
                return firstOperand * secondOperand;
            } else if (operator === '/') {
                return firstOperand / secondOperand;
            }

            return secondOperand;
        }

        function resetCalculator() {
            calculator.displayValue = '0';
            calculator.firstOperand = null;
            calculator.waitingForSecondOperand = false;
            calculator.operator = null;
        }

        $('.calculator').on('click', 'button', function() {
            const { textContent } = this;
            
            if ($(this).attr('data-number') !== undefined) {
                inputDigit(textContent);
            } else if ($(this).attr('data-operation') !== undefined) {
                handleOperator(textContent);
            } else if ($(this).attr('data-equals') !== undefined) {
                handleOperator('=');
            } else if ($(this).attr('data-all-clear') !== undefined) {
                resetCalculator();
            } else if ($(this).attr('data-delete') !== undefined) {
                calculator.displayValue = calculator.displayValue.slice(0, -1) || '0';
            } else if (textContent === '.') {
                inputDecimal(textContent);
            }

            updateDisplay();
        });

        updateDisplay();
    });
</script>