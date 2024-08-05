(function ($) {
    let loader = $(".chocoletrasPlg-spiner");
    $(document).ready(function () {
        const keyMap = {
            '0': '0.png', '1': '1.png', '2': '2.png', '3': '3.png', '4': '4.png', '5': '5.png',
            '6': '6.png', '7': '7.png', '8': '8.png', '9': '9.png', ' ': { heart: 'heart.png', star: 'star.png' },
            'A': 'a.png', 'Á': 'a.png', 'B': 'b.png', 'C': 'c.png', 'D': 'd.png', 'E': 'e.png', 'É': 'e.png', 'F': 'f.png',
            'G': 'g.png', 'H': 'h.png', 'I': 'i.png', 'Í': 'i.png', 'J': 'j.png', 'K': 'k.png', 'L': 'l.png',
            'M': 'm.png', 'N': 'n.png', 'O': 'o.png', 'Ó': 'o.png', 'P': 'p.png', 'Q': 'q.png', 'R': 'r.png',
            'S': 's.png', 'T': 't.png', 'U': 'u.png', 'Ú': 'u.png', 'V': 'v.png', 'W': 'w.png', 'X': 'x.png',
            'Y': 'y.png', 'Z': 'z.png',
            'Ñ': 'n1.png', 'ñ': 'n1.png', 'Ç': 'c1.png',
            '?': 'que.png', '¡': 'exclm1.png',
            '!': 'exclm.png', '¿': 'que1.png',
            ',': 'coma.png', '&': 'and.png'
        };

        function generateImages(text, $typewriterInner, spaceSymbol) {
            let chocoType = $("#chocoBase").val();
            console.log('Current chocoType:', chocoType); // Debugging line
            $typewriterInner.empty();
            const words = text.split(spaceSymbol);
            words.forEach((word, index) => {
                let $wordDiv = $('<div>').addClass('word');
                let imgCount = 0;

                for (const char of word) {
                    let imgFileName;
                    if (char === spaceSymbol) {
                        imgFileName = keyMap[' '][$('#letras').val()];
                    } else {
                        imgFileName = keyMap[char.toUpperCase()] || keyMap[char];
                    }

                    if (imgFileName) {
                        const imgPath = `${ajax_variables.pluginUrl}img/letters/${chocoType}/${imgFileName}`;
                        const $img = $('<img>').attr('src', imgPath).addClass('letter-img');

                        // Check if imgCount exceeds 15
                        if (imgCount >= 15) {
                            $typewriterInner.append($wordDiv);
                            $wordDiv = $('<div>').addClass('word');
                            imgCount = 0;
                        }

                        $wordDiv.append($img);
                        imgCount++;
                    }
                }

                $typewriterInner.append($wordDiv);

                if (index < words.length - 1) {
                    const imgPath = `${ajax_variables.pluginUrl}img/letters/${chocoType}/${keyMap[' '][$('#letras').val()]}`;
                    const $img = $('<img>').attr('src', imgPath).addClass('letter-img');
                    $typewriterInner.append($img);
                }
            });

            let maxChildCount = 0;
            $('.typewriterInner .word').each(function () {
                const childCount = $(this).children().length;
                if (childCount > maxChildCount) {
                    maxChildCount = childCount;
                }
            });

            if (maxChildCount > 10) {
                if ($(window).width() < 600) {
                    $('.typewriterInner .word').children().css('max-width', '16px');
                    $('.typewriterInner .letter-img').css('max-width', '16px');
                } else {
                    $('.typewriterInner .word').children().css('max-width', '35px');
                    $('.typewriterInner .letter-img').css('max-width', '35px');
                }
            } else if (maxChildCount > 7) {
                if ($(window).width() < 600) {
                    $('.typewriterInner .word').children().css('max-width', '25px');
                    $('.typewriterInner .letter-img').css('max-width', '25px');
                } else {
                    $('.typewriterInner .word').children().css('max-width', '50px');
                    $('.typewriterInner .letter-img').css('max-width', '50px');
                }
            }

        }

        function calculateTotalPrice() {
            let totalPrice = 0;
            let totalCount = 0;
            const pricePerCharacter = Number($("#precLetras").val());
            const pricePerSymbol = Number($("#precCoraz").val());
            const minPrice = parseFloat(ajax_variables.gastoMinimo);
            const shippingCost = parseFloat(ajax_variables.precEnvio);

            function calculatePrice(text) {
                let price = 0;
                let count = 0;
                for (let i = 0; i < text.length; i++) {
                    const char = text[i];
                    if (char === '✯' || char === '♥') {
                        price += pricePerSymbol;
                    } else {
                        price += pricePerCharacter;
                    }
                    count++;
                }
                return { price: price, count: count };
            }

            function getPriceForInput($input) {
                const { price, count } = calculatePrice($input.val());
                if (price < minPrice) {
                    totalPrice += minPrice;
                } else {
                    totalPrice += minPrice + (price - minPrice);
                }
                totalCount += count;
            }

            // Calculate the price for #getText field
            getPriceForInput(jQuery('#getText'));

            // Calculate the price for .fraseInput fields
            jQuery('.fraseInput').each(function () {
                getPriceForInput(jQuery(this));
            });

            // Add shipping cost to the total price
            totalPrice += shippingCost;

            jQuery('#ctf_form #counter').text(totalPrice.toFixed(2));
            jQuery('#actual').text(totalCount);
            jQuery('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val(totalPrice.toFixed(2));
        }




        function attachInputHandler($input, $typewriterInner) {
            function updateText() {
                const selectedSymbol = $('#letras').val() === 'heart' ? '♥' : '✯';
                let inputText = $input.val();
                inputText = inputText.replace(/♥|✯/g, selectedSymbol); // Replace all hearts and stars with the selected symbol
                inputText = inputText.replace(/ /g, selectedSymbol).toUpperCase();
                $input.val(inputText);
                generateImages(inputText, $typewriterInner, selectedSymbol);
                calculateTotalPrice(); // Trigger price calculation on input change
                checkInputs(); // Check inputs on change
            }

            $input.on('input', updateText);
            $('#letras').on('change', updateText);
        }

        function checkInputs() {
            let allFilled = true;

            if ($.trim($('#getText').val()) === "") {
                allFilled = false;
            }

            $('.fraseInput').each(function () {
                if ($.trim($(this).val()) === "") {
                    allFilled = false;
                }
            });

            if (allFilled) {
                $(".dummyImg").css('display', 'none');
                $("#addNewFrase").removeAttr('disabled');
                $("#ctf_form .action-button").removeAttr('disabled');
            } else {
                $(".dummyImg").css('display', 'block');
                $("#addNewFrase").prop('disabled', true);
                $("#ctf_form .action-button").prop('disabled', true);
            }
        }

        attachInputHandler($('#getText'), $('#typewriter .typewriterInner'));

        $("#ctf_form #getText").on("keyup", function () {
            checkInputs();
        });

        let typewriterCounter = 1;
        $("#addNewFrase").click(function () {
            const newTypewriterInnerId = `typewriterInner_${typewriterCounter++}`;
            const newFrasePanelId = `frasePanel_${typewriterCounter}`;

            const $newFrasePanel = $(`
                <div class="frasePanel" id="${newFrasePanelId}">
                    <div class="closeBtnTyper">
                        <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM8.96963 8.96965C9.26252 8.67676 9.73739 8.67676 10.0303 8.96965L12 10.9393L13.9696 8.96967C14.2625 8.67678 14.7374 8.67678 15.0303 8.96967C15.3232 9.26256 15.3232 9.73744 15.0303 10.0303L13.0606 12L15.0303 13.9696C15.3232 14.2625 15.3232 14.7374 15.0303 15.0303C14.7374 15.3232 14.2625 15.3232 13.9696 15.0303L12 13.0607L10.0303 15.0303C9.73742 15.3232 9.26254 15.3232 8.96965 15.0303C8.67676 14.7374 8.67676 14.2625 8.96965 13.9697L10.9393 12L8.96963 10.0303C8.67673 9.73742 8.67673 9.26254 8.96963 8.96965Z" fill="#E64C3C" />
                        </svg>
                    </div>
                    <input class="fraseInput" type="text" placeholder="Escriba su frase aquí.." maxlength="${ajax_variables.maxCaracteres}" required>
                </div>
            `);
            $('.fraseWrapper').append($newFrasePanel);
            const $newTypewriterInner = $(`<div class="typewriterInner" id="${newTypewriterInnerId}"></div>`);
            $('#typewriter').append($newTypewriterInner);
            const $newInput = $newFrasePanel.find('.fraseInput');
            attachInputHandler($newInput, $newTypewriterInner);
            checkInputs();
            // Attach click event handler to the close button
            $newFrasePanel.find('.closeBtnTyper').click(function () {
                $newFrasePanel.remove(); // Remove the frase panel
                $newTypewriterInner.remove(); // Remove the typewriter inner element
                calculateTotalPrice(); // Recalculate the total price
                checkInputs(); // Check inputs on remove
            });
        });


        let screenshotPaths = [];
        let screenshotData = [];
        let typewriterScreenshotPath = '';

        function saveScreenshots(screenshots, callback) {
            $.ajax({
                type: "POST",
                url: `${ajax_variables.pluginUrl}utils/save_screenshot.php`,
                data: {
                    screenshots: screenshots
                },
                success: function (response) {
                    console.log("Screenshots saved successfully.");
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        screenshotPaths = data.filepaths.slice(1); // All paths except the first one
                        typewriterScreenshotPath = data.filepaths[0]; // First path is for the #typewriter div
                    }
                    callback(null, response);
                },
                error: function (error) {
                    console.error("Error saving screenshots:", error);
                    callback(error);
                }
            });
        }

        function takeScreenshot(element, filename, callback) {
            html2canvas(element, {
                scale: 1,  // Reduce scale to lower resolution
                useCORS: true
            }).then(canvas => {
                // Reduce the quality and size by setting a lower quality value (0.1 = 10% quality)
                const imgData = canvas.toDataURL('image/png');
                callback(null, {
                    imgBase64: imgData,
                    filename: filename
                });
            }).catch(error => {
                console.error("Error capturing screenshot of element", error);
                callback(error);
            });
        }


        $("#continuarBTN").on('click', function () {
            $('.priceCounter').text($(".chocoletrasPlg__wrapperCode-dataUser-form-input-price").val());

            const elementsToCapture = $(".typewriterInner").toArray();
            const typewriterElement = $("#typewriter")[0];
            const timestamp = new Date().getTime();

            let captureCount = 0;

            // Take screenshot of #typewriter first
            takeScreenshot(typewriterElement, 'typewriter_screenshot_' + timestamp + '.png', function (error, typewriterScreenshot) {
                if (!error) {
                    screenshotData.push(typewriterScreenshot);
                    captureCount++;

                    elementsToCapture.forEach((element, index) => {
                        const uniqueFilename = 'screenshot_' + timestamp + '_' + (index + 1) + '.png';

                        takeScreenshot(element, uniqueFilename, function (error, screenshot) {
                            if (!error) {
                                screenshotData.push(screenshot);
                                captureCount++;

                                // If all elements are processed, save all screenshots
                                if (captureCount === elementsToCapture.length + 1) { // +1 for the #typewriter screenshot
                                    saveScreenshots(screenshotData, function (error, response) {
                                        if (!error) {
                                            console.log("All screenshots saved successfully.");
                                        }
                                    });
                                }
                            }
                        });
                    });
                }
            });
        });




        $("#ctf_form").on("submit", function (event) {
            event.preventDefault();
            loader.css('height', '100%');
            console.log('submission');
            const mainText = [$('#getText').val()];
            const chocoType = $('#chocoBase').val();
            const priceTotal = $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val();
            const fullName = $("#fname").val();
            const email = $("#email").val();
            const tel = $("#chocoTel").val();
            const postal = $("#cp").val();
            const city = $("#city").val();
            const province = $("#province").val();
            const address = $("#address").val();
            let picDate = $("#picDate").val();
            const shippingType = $("#ExpressActivator").val();
            const message = $("#message").val();
            const uoi = $("#uniqueOrderID").val();
            const coupon = $("#usedCoupon").val();

            if (!picDate) {
                picDate = new Date().toISOString().slice(0, 10);
            }

            $('.fraseInput').each(function () {
                mainText.push($(this).val());
            });
            
            const dataToSend = {
                action: 'test_action',
                mainText: JSON.stringify(mainText),
                chocoType: chocoType,
                priceTotal: priceTotal,
                fname: fullName,
                email: email,
                tel: tel,
                postal: postal,
                city: city,
                province: province,
                address: address,
                message: message,
                uoi: uoi,
                coupon: coupon,
                screens: JSON.stringify(screenshotPaths),
                featured: typewriterScreenshotPath,
                picDate: picDate,
                shippingType: shippingType,
                nonce: ajax_variables.nonce
            };

            console.log(dataToSend);

            $.ajax({
                type: "POST",
                url: ajax_variables.ajax_url,
                data: dataToSend,
                success: function (response) {
                    console.log("Response from server: ", response);
                    const parsedResponse = JSON.parse(response);

                    if (parsedResponse.Datos.Status) {
                        console.info("Process succeeded: ", parsedResponse.Datos);
                        console.log("Inserted ID: ", parsedResponse.Datos.inserted_id);

                        const cookieData = {
                            inserted_id: parsedResponse.Datos.inserted_id,
                            mainText: mainText,
                            chocoType: chocoType,
                            priceTotal: priceTotal,
                            fname: fullName,
                            email: email,
                            tel: tel,
                            postal: postal,
                            city: city,
                            province: province,
                            address: address,
                            picDate: picDate,
                            shippingType: shippingType,
                            express: new Date().toISOString(),
                            message: message,
                            uoi: uoi,
                            coupon: coupon,
                            screenshots: screenshotPaths,
                            productBanner: typewriterScreenshotPath,
                        };

                        const cookieValue = encodeURIComponent(JSON.stringify(cookieData));

                        setCookie('chocol_cookie', true);
                        setCookie('chocoletraOrderData', cookieValue);

                    } else {
                        console.error("Process failed: ", parsedResponse.Datos);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                },
                complete: function () {
                    location.reload();
                }
            });
        });

        function removeCookie(name) {
            document.cookie = name + '=; Max-Age=0; path=/;';
        }

        jQuery(document).ready(function () {
            let selectedGatway = null;
            let paymentMethod = ""; // Declare paymentMethod outside the click event handler

            $(".paymentPanel .paymentCard").on('click', function () {
                $(".paymentPanel .paymentCard").removeClass('active');
                $(this).addClass("active");
                selectedGatway = $(this).attr("data-gatway");

                // Reset paymentMethod on each click
                paymentMethod = "";

                if (selectedGatway === 'paypal') {
                    paymentMethod = "PayPal";
                } else if (selectedGatway === 'redsys') {
                    paymentMethod = "Redsys";
                } else if (selectedGatway === 'bizum') {
                    paymentMethod = "Bizum";
                } else if (selectedGatway === 'google') {
                    paymentMethod = "Google Pay";
                } else if (selectedGatway === 'apple') {
                    paymentMethod = "Apple Pay";
                }

                $("#selectedPayment").val(paymentMethod);
                console.log(paymentMethod);

                // Show loader
                $("#loader").css('height', '100%');

                var cookieValue = getCookie("chocoletraOrderData");
                if (cookieValue) {
                    var orderData = JSON.parse(decodeURIComponent(cookieValue));
                    orderData.payment = paymentMethod;
                    setCookie("chocoletraOrderData", encodeURIComponent(JSON.stringify(orderData)));
                }

                console.log(orderData); // To check if the cookie is updated correctly
                return selectedGatway; // Return only selectedGatway here
            });

            $("#proceedPayment").on('click', function () {
                console.log(selectedGatway, paymentMethod);

                var checkCookieUpdated = setInterval(function () {
                    var updatedCookieValue = getCookie("chocoletraOrderData");
                    if (updatedCookieValue) {
                        var updatedOrderData = JSON.parse(decodeURIComponent(updatedCookieValue));
                        if (updatedOrderData.payment === paymentMethod) {
                            clearInterval(checkCookieUpdated);

                            // Hide loader after updating the cookie
                            $("#loader").css('height', '0%');

                            if (selectedGatway === 'paypal') {
                                $("#selectedPayment").val("PayPal");
                                $("#payPayPal").submit();
                            } else if (selectedGatway === 'redsys') {
                                $("#selectedPayment").val("Redsys");
                                $("#payRedsys").submit();
                            } else if (selectedGatway === 'bizum') {
                                $("#selectedPayment").val("Bizum");
                                $("#payBizum").submit();
                            } else if (selectedGatway === 'google') {
                                $("#selectedPayment").val("Google Pay");
                                $("#payGoogle").submit();
                            } else if (selectedGatway === 'apple') {
                                $("#selectedPayment").val("Apple Pay");
                                $("#payGoogle").submit();
                            } else {
                                alert("Select any of the payment first!");
                            }
                        }
                    }
                }, 200); // Check every 200ms
            });

            jQuery("#cancelProcessPaiment").on('click', function () {
                loader.css('height', '100%');
                $.ajax({
                    type: "post",
                    url: ajax_variables.ajax_url,
                    dataType: "json",
                    data: "action=cancelProcess",
                    error: function (e) {
                        console.log(e);
                    },
                    success: function (e) {
                        removeCookie('chocoletraOrderData');
                        removeCookie('chocol_cookie');
                        removeCookie('paypamentType');
                        location.reload();
                    },
                });
            });
        });

        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }

        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = "expires=" + date.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }

        let currentShippingMethod = 'normal';
        $('#ctf_form .shippingPanel>.expressShipping').on('click', function () {
            if (currentShippingMethod !== 'express') {
                $('#ctf_form .shippingPanel>div').removeClass('selected');
                $('#ctf_form .shippingPanel .standardShipping').hide();
                $('#ctf_form .shippingPanel .shippingExpress').show();
                $(this).addClass('selected');

                $("#ExpressActivator").val('on');
                currentShippingMethod = 'express';

                let getPrice = $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val();
                let totalPrice = Number(getPrice) + Number($("#expressShipingPrice").val());
                $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val(totalPrice);

                $('.priceCounter').text(totalPrice);
            }
        });

        $('#ctf_form .shippingPanel>.normalShipping').on('click', function () {
            if (currentShippingMethod !== 'normal') {
                $('#ctf_form .shippingPanel>div').removeClass('selected');
                $('#ctf_form .shippingPanel .standardShipping').show();
                $('#ctf_form .shippingPanel .shippingExpress').hide();
                $(this).addClass('selected');

                $("#ExpressActivator").val('off');
                currentShippingMethod = 'normal';

                let getPrice = $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val();
                let totalPrice = Number(getPrice) - Number($("#expressShipingPrice").val());
                $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val(totalPrice);
                $('.priceCounter').text(totalPrice);
            }
        });


        var current_fs, next_fs, previous_fs; // Fieldsets
        var current = 1; // Current step
        var steps = $("fieldset").length; // Number of fieldsets

        setProgressBar(current);

        // Next button click
        $(".next").click(function () {
            current_fs = $(this).parents('fieldset');
            next_fs = $(this).parents('fieldset').next();

            // Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            // Show the next fieldset
            next_fs.show();
            // Hide the current fieldset with style
            current_fs.animate({ opacity: 0 }, {
                step: function (now) {
                    // for making fieldset appear animation
                    var opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({ 'opacity': opacity });
                },
                duration: 500
            });
            setProgressBar(++current);
        });

        // Previous button click
        $(".previous").click(function () {
            current_fs = $(this).parents('fieldset');
            previous_fs = $(this).parents('fieldset').prev();

            // Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            // Show the previous fieldset
            previous_fs.show();

            // Hide the current fieldset with style
            current_fs.animate({ opacity: 0 }, {
                step: function (now) {
                    // for making fieldset appear animation
                    var opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({ 'opacity': opacity });
                },
                duration: 500
            });
            setProgressBar(--current);
        });

        // Progress bar click
        $("#progressbar li:first").click(function () {
            var index = $(this).index();
            if (index + 1 !== current) {
                // Remove active class from all progress bar steps
                $("#progressbar li").removeClass("active");

                // Add active class to the clicked step and all previous steps
                for (var i = 0; i <= index; i++) {
                    $("#progressbar li").eq(i).addClass("active");
                }

                // Hide the current fieldset
                current_fs = $("fieldset:visible");
                current_fs.animate({ opacity: 0 }, {
                    step: function (now) {
                        var opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                    },
                    duration: 500
                });

                // Show the corresponding fieldset
                next_fs = $("fieldset").eq(index);
                next_fs.show().css({ 'opacity': 0 }).animate({ opacity: 1 }, 500);

                // Update the current step
                current = index + 1;
                setProgressBar(current);
            }
        });

        function setProgressBar(curStep) {
            var percent = parseFloat(100 / steps) * curStep;
            percent = percent.toFixed();
            $(".progress-bar")
                .css("width", percent + "%")
        }


        $(".submit").click(function () {
            return false;
        })



        $.ajax({
            url: ajax_variables.ajax_url,
            method: 'POST',
            data: {
                action: 'get_calendar_settings'
            },
            success: function (response) {
                // console.log(response

                var disableDays = response.disable_days || [];
                var disableDatesString = response.disable_dates || '';
                var disableMonthsDays = response.disable_months_days || { months: [], days: [] };

                var disableDates = disableDatesString.split(',').map(function (date) {
                    return date.trim();
                });

                // console.log("Disable Days:", disableDays); 
                // console.log("Disable Dates:", disableDates); 
                // console.log("Disable Months and Days:", disableMonthsDays);

                $("#picDate").flatpickr({
                    minDate: "today",
                    defaultDate: "today",
                    dateFormat: "Y-m-d",
                    locale: "es",
                    disable: [
                        function (date) {
                            return disableDays.includes(date.getDay().toString());
                        },
                        // Disable specific dates
                        function (date) {
                            var formattedDate = flatpickr.formatDate(date, "Y-m-d");
                            return disableDates.includes(formattedDate);
                        },
                        function (date) {
                            var month = date.getMonth();
                            var day = date.getDay();
                            if (disableMonthsDays.months.includes(month.toString()) && disableMonthsDays.days.includes(day.toString())) {
                                return true;
                            }
                            return false;
                        }
                    ],
                    onChange: function (selectedDates, dateStr, instance) {
                        var selectedDate = selectedDates[0];
                        var selectedDay = selectedDate.getDay();
                        var priceInput = document.querySelector(".chocoletrasPlg__wrapperCode-dataUser-form-input-price");
                        var previousDate = instance._previousDate || null;
                        var previousDay = previousDate ? previousDate.getDay() : null;

                        if (priceInput) {
                            var currentValue = parseFloat(priceInput.value) || 0;

                            if (selectedDay === 6 && previousDay !== 6) {
                                var finalValSat = currentValue + 5;
                                priceInput.value = finalValSat;
                                $('.priceCounter').text(finalValSat);
                                $('#counter').text(finalValSat);
                            } else if (selectedDay !== 6 && previousDay === 6) {
                                var finalValNotSat = currentValue - 5;
                                priceInput.value = finalValNotSat;
                                $('.priceCounter').text(finalValNotSat);
                                $('#counter').text(finalValNotSat);
                            }

                            instance._previousDate = selectedDate;
                        }
                    }
                });


            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });


        let couponCondition = false;
        $('#couponApply').click(function () {
            if (couponCondition) {
                alert('You already use a coupon before In this Order.')
                return;
            }
            var couponCode = $('#coupon').val();

            if (couponCode === '') {
                alert('Please enter a coupon code.');
                return;
            }

            $.ajax({
                url: ajax_variables.ajax_url,
                method: 'POST',
                data: {
                    action: 'validate_coupon',
                    coupon: couponCode
                },
                success: function (response) {
                    if (response.success) {
                        $("#usedCoupon").val(couponCode);
                        alert('Coupon is valid. Discount: ' + response.data.discount + ' ' + response.data.type + '. Remaining uses: ' + response.data.remaining_usage);
                        couponCondition = true;
                        if (response.data.type === 'fixed') {
                            let priceTotal = $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val();
                            let afterDiscount = Number(priceTotal) - response.data.discount;
                            $('.priceCounter').text(afterDiscount);
                            $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val(afterDiscount);
                        } else if (response.data.type === 'percentage') {
                            let priceTotal = $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val();
                            let discountValue = (Number(priceTotal) * response.data.discount) / 100;
                            let afterDiscount = Number(priceTotal) - discountValue;
                            $('.priceCounter').text(afterDiscount.toFixed(2));
                            $('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val(afterDiscount.toFixed(2));
                        } else {
                            console.log('Unknown discount type');
                        }

                    } else {
                        alert('Error: ' + response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });

        $(".couponSection p").on('click', function () {
            $(this).parents('.couponSection').toggleClass('open');
        })
    });

    function getQueryParam(param) {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    if (getQueryParam("payment") === "true") {
        var counter = 40;
        var interval = setInterval(function () {
            counter--;
            $("#countdownRedirect").text(counter);
            if (counter <= 0) {
                clearInterval(interval);
                window.location.href = ajax_variables.pluginPageUrl;
            }
        }, 1000);
    } else {
        $(".thankYouCard span").hide();
    }

    $("#pricingTableBtn").on('click', function () {
        $("#pricingTable").toggleClass('open');
    })

    const typedText = document.querySelector(".typed-text");
    const cursor = document.querySelector(".cursor");

    const textArray = ["Tu Frase", "Tus Deseos", "Tus Saludos"];

    let textArrayIndex = 0;
    let charIndex = 0;

    const erase = () => {
        if (charIndex > 0) {
            cursor.classList.remove('blink');
            typedText.textContent = textArray[textArrayIndex].slice(0, charIndex - 1);
            charIndex--;
            setTimeout(erase, 80);
        } else {
            cursor.classList.add('blink');
            textArrayIndex++;
            if (textArrayIndex > textArray.length - 1) {
                textArrayIndex = 0;
            }
            setTimeout(type, 1000);
        }
    }

    const type = () => {
        if (charIndex <= textArray[textArrayIndex].length - 1) {
            cursor.classList.remove('blink');
            typedText.textContent += textArray[textArrayIndex].charAt(charIndex);
            charIndex++;
            setTimeout(type, 120);
        } else {
            cursor.classList.add('blink');
            setTimeout(erase, 1000);
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        type();
    })

}(jQuery));
