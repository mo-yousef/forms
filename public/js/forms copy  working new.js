/*******************************************************
 * MULTI-LANGUAGE TRANSLATIONS + TRANSLATION HELPER
 * -----------------------------------------------------
 * 1) Define all user-facing text in multiple languages
 * 2) Create a helper function t(key) that reads <html lang="">
 * 3) Fallback to English if no matching language/key is found
 *******************************************************/

function clearFieldError($form, fieldName) {
  if (fieldName === "full_phone") return;

  const $errorSpan = $form.find(`#${fieldName}_error`);
  const $field = $form.find(`[name="${fieldName}"]`);

  if ($errorSpan.length) {
    $errorSpan.text("").removeClass("active");
  }
  if ($field.length) {
    $field.removeClass("error-field");
    if ($field.attr("type") === "tel") {
      $field.closest(".phone-input-container").removeClass("error-field error");
    }
  }
}

// Define these functions globally before any other code
function showFieldError($form, fieldName, message) {
  const $errorSpan = $form.find(`#${fieldName}_error`);
  const $field = $form.find(`[name="${fieldName}"]`);

  if ($errorSpan.length) {
    $errorSpan.text(message).addClass("active");
  }

  if ($field.length) {
    $field.addClass("error-field");
    if ($field.attr("type") === "tel") {
      $field.closest(".phone-input-container").addClass("error-field error");
    }
  }
}

// 1) Translations object
const translations = {
  "en-US": {
    // Validation messages
    requiredField: "This field is required.",
    invalidName: "Please enter a valid name.",
    invalidEmail: "Please enter a valid email.",
    invalidPhone: "Please enter a valid phone number.",
    requiredTerms: "You must agree to the Terms & Conditions.",
    accountTypeRequired: "Please select an account type.",
    invalidIDNumber: "Please enter a valid ID Number.",
    invalidNumber: "Please enter a valid number.",
    nonNegativeNumber: "Please enter a non-negative number.",
    invalidURL: "Please enter a valid URL.",
    firstNameRequired: "First name is required.",
    lastNameRequired: "Last name is required.",
    firstNameLettersOnly: "First name must contain letters only.",
    lastNameLettersOnly: "Last name must contain letters only.",
    emailRequired: "Email is required.",
    selectTradingPlatform: "Please select a trading platform (MT4 or MT5).",
    pleaseEnterMQID: "Please enter your MQID Number.",
    pleaseEnterAddress: "Please enter your Address.",
    tradingAccountRequired: "Trading account number is required.",
    numericAccountOnly: "Please enter a numeric account number.",

    // Generic notifications/messages
    invalidSubmission: "Invalid submission detected.",
    spamDetected: "Spam detected! Refresh and try again.",
    anErrorOccurred: "An error occurred. Please try again.",
    requestTimedOut: "Request timed out. Please check your connection and try again.",
    noInternet: "No internet connection. Please check your connection and try again.",
    accessDenied: "Access denied. Please refresh and try again.",
    endpointNotFound: "Form submission endpoint not found. Please try again later.",
    serverError: "Server error occurred. Please try again later.",
    invalidServerResponse: "Invalid server response. Please try again.",
    formSubmittedSuccessfully: "Form submitted successfully!",
  },

  "es-ES": {
    // Validation messages
    requiredField: "Campo obligatorio.",
    invalidName: "Por favor, ingresa un nombre válido.",
    invalidEmail: "Por favor, ingresa un correo electrónico válido.",
    invalidPhone: "Por favor, ingresa un número de teléfono válido.",
    requiredTerms: "Debes aceptar los Términos y Condiciones.",
    accountTypeRequired: "Por favor, selecciona un tipo de cuenta.",
    invalidIDNumber: "Por favor, ingresa un número de identificación válido.",
    invalidNumber: "Por favor, ingresa un número válido.",
    nonNegativeNumber: "Por favor, ingresa un número no negativo.",
    invalidURL: "Por favor, ingresa una URL válida.",
    firstNameRequired: "El nombre es obligatorio.",
    lastNameRequired: "El apellido es obligatorio.",
    firstNameLettersOnly: "El nombre solo debe contener letras.",
    lastNameLettersOnly: "El apellido solo debe contener letras.",
    emailRequired: "El correo electrónico es obligatorio.",
    selectTradingPlatform: "Por favor, selecciona una plataforma de trading (MT4 o MT5).",
    pleaseEnterMQID: "Por favor, ingresa tu número MQID.",
    pleaseEnterAddress: "Por favor, ingresa tu dirección.",
    tradingAccountRequired: "El número de cuenta de trading es obligatorio.",
    numericAccountOnly: "Por favor, ingresa un número de cuenta numérico.",

    // Generic notifications/messages
    invalidSubmission: "Envío inválido detectado.",
    spamDetected: "¡Se detectó spam! Actualiza la página e inténtalo de nuevo.",
    anErrorOccurred: "Ocurrió un error. Por favor, inténtalo de nuevo.",
    requestTimedOut: "Se acabó el tiempo de espera. Verifica tu conexión e inténtalo de nuevo.",
    noInternet: "No hay conexión a internet. Verifica tu conexión e inténtalo de nuevo.",
    accessDenied: "Acceso denegado. Por favor, actualiza la página e inténtalo de nuevo.",
    endpointNotFound: "Punto de envío no encontrado. Por favor, inténtalo más tarde.",
    serverError: "Ocurrió un error en el servidor. Por favor, inténtalo más tarde.",
    invalidServerResponse: "Respuesta del servidor inválida. Inténtalo de nuevo.",
    formSubmittedSuccessfully: "¡Formulario enviado con éxito!",
  },

  "fr-FR": {
    // Validation messages
    requiredField: "Ce champ est requis.",
    invalidName: "Veuillez saisir un nom valide.",
    invalidEmail: "Veuillez saisir une adresse e-mail valide.",
    invalidPhone: "Veuillez saisir un numéro de téléphone valide.",
    requiredTerms: "Vous devez accepter les Termes et Conditions.",
    accountTypeRequired: "Veuillez sélectionner un type de compte.",
    invalidIDNumber: "Veuillez saisir un numéro d'identification valide.",
    invalidNumber: "Veuillez saisir un nombre valide.",
    nonNegativeNumber: "Veuillez saisir un nombre non négatif.",
    invalidURL: "Veuillez saisir une URL valide.",
    firstNameRequired: "Le prénom est requis.",
    lastNameRequired: "Le nom est requis.",
    firstNameLettersOnly: "Le prénom ne doit contenir que des lettres.",
    lastNameLettersOnly: "Le nom ne doit contenir que des lettres.",
    emailRequired: "L'adresse e-mail est requise.",
    selectTradingPlatform: "Veuillez sélectionner une plateforme de trading (MT4 ou MT5).",
    pleaseEnterMQID: "Veuillez saisir votre numéro MQID.",
    pleaseEnterAddress: "Veuillez saisir votre adresse.",
    tradingAccountRequired: "Le numéro de compte de trading est requis.",
    numericAccountOnly: "Veuillez saisir un numéro de compte numérique.",

    // Generic notifications/messages
    invalidSubmission: "Envoi invalide détecté.",
    spamDetected: "Spam détecté ! Actualisez la page et réessayez.",
    anErrorOccurred: "Une erreur est survenue. Veuillez réessayer.",
    requestTimedOut: "La requête a expiré. Vérifiez votre connexion et réessayez.",
    noInternet: "Pas de connexion Internet. Vérifiez votre connexion et réessayez.",
    accessDenied: "Accès refusé. Veuillez actualiser la page et réessayer.",
    endpointNotFound: "Point de soumission introuvable. Réessayez plus tard.",
    serverError: "Erreur de serveur. Veuillez réessayer plus tard.",
    invalidServerResponse: "Réponse du serveur invalide. Veuillez réessayer.",
    formSubmittedSuccessfully: "Formulaire envoyé avec succès !",
  },

  "pt-br": {
    // Validation messages
    requiredField: "Este campo é obrigatório.",
    invalidName: "Por favor, insira um nome válido.",
    invalidEmail: "Por favor, insira um e-mail válido.",
    invalidPhone: "Por favor, insira um número de telefone válido.",
    requiredTerms: "Você deve concordar com os Termos e Condições.",
    accountTypeRequired: "Por favor, selecione um tipo de conta.",
    invalidIDNumber: "Por favor, insira um número de identificação válido.",
    invalidNumber: "Por favor, insira um número válido.",
    nonNegativeNumber: "Por favor, insira um número não negativo.",
    invalidURL: "Por favor, insira uma URL válida.",
    firstNameRequired: "O primeiro nome é obrigatório.",
    lastNameRequired: "O sobrenome é obrigatório.",
    firstNameLettersOnly: "O primeiro nome deve conter apenas letras.",
    lastNameLettersOnly: "O sobrenome deve conter apenas letras.",
    emailRequired: "O e-mail é obrigatório.",
    selectTradingPlatform: "Por favor, selecione uma plataforma de trading (MT4 ou MT5).",
    pleaseEnterMQID: "Por favor, insira seu número MQID.",
    pleaseEnterAddress: "Por favor, insira seu endereço.",
    tradingAccountRequired: "O número da conta de trading é obrigatório.",
    numericAccountOnly: "Por favor, insira um número de conta numérico.",

    // Generic notifications/messages
    invalidSubmission: "Envio inválido detectado.",
    spamDetected: "Spam detectado! Atualize a página e tente novamente.",
    anErrorOccurred: "Ocorreu um erro. Por favor, tente novamente.",
    requestTimedOut: "Tempo de solicitação esgotado. Verifique sua conexão e tente novamente.",
    noInternet: "Sem conexão com a Internet. Verifique sua conexão e tente novamente.",
    accessDenied: "Acesso negado. Por favor, atualize a página e tente novamente.",
    endpointNotFound: "Endpoint de envio não encontrado. Tente novamente mais tarde.",
    serverError: "Erro no servidor. Tente novamente mais tarde.",
    invalidServerResponse: "Resposta inválida do servidor. Tente novamente.",
    formSubmittedSuccessfully: "Formulário enviado com sucesso!",
  },
};

// 2) Helper function t(key): returns the localized message
function t(key) {
  // For example, <html lang="fr-FR" />
  const htmlLang = document.documentElement.getAttribute("lang") || "en-US";

  // If we have translations[lang] and translations[lang][key], return it.
  if (translations[htmlLang] && translations[htmlLang][key]) {
    return translations[htmlLang][key];
  }
  // Otherwise fallback to English if it exists
  else if (translations["en-US"] && translations["en-US"][key]) {
    return translations["en-US"][key];
  }
  // If no match, return the key itself
  return key;
}

/*******************************************************
 *  FXC FORMS - MASTER JS FILE
 *  -----------------------------------------------------
 *  This file handles:
 *  - Form validations (names, email, phone, etc.)
 *  - Debounced email checks (for the Bonus Request form)
 *  - Country + phone code auto-detection
 *  - Custom notifications (toast/overlay)
 *  - Dynamic/inline error handling
 *  - Select2 initialization for country fields
 *  - reCAPTCHA (v3) handling
 *  - Custom dropdown logic
 *  - FDB form-specific validation
 *******************************************************/
console.log("FORMS JS");
/**
 * Global flag to track if a form submission was attempted.
 * This lets us decide whether to do inline validation on "input" events.
 */
let submissionAttempted = false;

/**
 * 1) Debounce function
 *    Used for delaying execution of a function after rapid calls.
 */
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

/**
 * 2) REGEX VALIDATION FUNCTIONS (Global)
 *    Basic name, email, and phone validators using regular expressions.
 */
function validateName(name) {
  // Letters and spaces only
  return /^[a-zA-Z\s]+$/.test(name);
}

function validateEmail(email) {
  // Basic pattern for email
  return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(email);
}

function validatePhone(phone) {
  // Supports optional +, parentheses, dashes, spaces
  return /^\+?[0-9\-\(\)\s]{7,15}$/.test(phone);
}

jQuery(document).ready(function ($) {
  /*******************************************************
   * 3. COUNTRY CODES (FLAGS) FOR PHONE DETECTION
   *******************************************************/
  const flags = {
    AF: "+93",
    AL: "+355",
    DZ: "+213",
    AD: "+376",
    AO: "+244",
    AG: "+1-268",
    AR: "+54",
    AM: "+374",
    AU: "+61",
    AT: "+43",
    AZ: "+994",
    BS: "+1-242",
    BH: "+973",
    BD: "+880",
    BB: "+1-246",
    BY: "+375",
    BE: "+32",
    BZ: "+501",
    BJ: "+229",
    BT: "+975",
    BO: "+591",
    BA: "+387",
    BW: "+267",
    BR: "+55",
    BN: "+673",
    BG: "+359",
    BF: "+226",
    BI: "+257",
    KH: "+855",
    CM: "+237",
    CA: "+1",
    CV: "+238",
    CF: "+236",
    TD: "+235",
    CL: "+56",
    CN: "+86",
    CO: "+57",
    KM: "+269",
    KY: "+1-345",
    CG: "+242",
    CD: "+243",
    CR: "+506",
    CI: "+225",
    HR: "+385",
    CU: "+53",
    CY: "+357",
    CZ: "+420",
    DK: "+45",
    DJ: "+253",
    DM: "+1-767",
    DO: "+1-809",
    EC: "+593",
    EG: "+20",
    SV: "+503",
    GQ: "+240",
    ER: "+291",
    EE: "+372",
    ET: "+251",
    FJ: "+679",
    FI: "+358",
    FR: "+33",
    GA: "+241",
    GM: "+220",
    GE: "+995",
    DE: "+49",
    GH: "+233",
    GR: "+30",
    GD: "+1-473",
    GT: "+502",
    GN: "+224",
    GW: "+245",
    GY: "+592",
    HT: "+509",
    HN: "+504",
    HU: "+36",
    IS: "+354",
    IN: "+91",
    ID: "+62",
    IR: "+98",
    IQ: "+964",
    IE: "+353",
    IL: "+972",
    IT: "+39",
    JM: "+1-876",
    JP: "+81",
    JO: "+962",
    KZ: "+7",
    KE: "+254",
    KI: "+686",
    KP: "+850",
    KR: "+82",
    KW: "+965",
    KG: "+996",
    LA: "+856",
    LV: "+371",
    LB: "+961",
    LS: "+266",
    LR: "+231",
    LY: "+218",
    LI: "+423",
    LT: "+370",
    LU: "+352",
    MK: "+389",
    MG: "+261",
    MW: "+265",
    MY: "+60",
    MV: "+960",
    ML: "+223",
    MT: "+356",
    MH: "+692",
    MR: "+222",
    MU: "+230",
    MX: "+52",
    FM: "+691",
    MD: "+373",
    MC: "+377",
    MN: "+976",
    ME: "+382",
    MA: "+212",
    MZ: "+258",
    MM: "+95",
    NA: "+264",
    NR: "+674",
    NP: "+977",
    NL: "+31",
    NZ: "+64",
    NI: "+505",
    NE: "+227",
    NG: "+234",
    NO: "+47",
    OM: "+968",
    PK: "+92",
    PW: "+680",
    PA: "+507",
    PG: "+675",
    PY: "+595",
    PE: "+51",
    PH: "+63",
    PL: "+48",
    PT: "+351",
    QA: "+974",
    RO: "+40",
    RU: "+7",
    RW: "+250",
    KN: "+1-869",
    LC: "+1-758",
    VC: "+1-784",
    WS: "+685",
    SM: "+378",
    ST: "+239",
    SA: "+966",
    SN: "+221",
    RS: "+381",
    SC: "+248",
    SL: "+232",
    SG: "+65",
    SK: "+421",
    SI: "+386",
    SB: "+677",
    SO: "+252",
    ZA: "+27",
    ES: "+34",
    LK: "+94",
    SD: "+249",
    SR: "+597",
    SE: "+46",
    CH: "+41",
    SY: "+963",
    TW: "+886",
    TJ: "+992",
    TZ: "+255",
    TH: "+66",
    TL: "+670",
    TG: "+228",
    TO: "+676",
    TT: "+1-868",
    TN: "+216",
    TR: "+90",
    TM: "+993",
    TV: "+688",
    UG: "+256",
    UA: "+380",
    AE: "+971",
    GB: "+44",
    US: "+1",
    UY: "+598",
    UZ: "+998",
    VU: "+678",
    VA: "+39",
    VE: "+58",
    VN: "+84",
    YE: "+967",
    ZM: "+260",
    ZW: "+263",
    // Additional territories
    AI: "+1-264",
    AS: "+1-684",
    AW: "+297",
    AX: "+358-18",
    BL: "+590",
    BM: "+1-441",
    BQ: "+599",
    CC: "+61",
    CK: "+682",
    CW: "+599",
    CX: "+61",
    EH: "+212",
    FK: "+500",
    FO: "+298",
    GF: "+594",
    GG: "+44-1481",
    GI: "+350",
    GL: "+299",
    GP: "+590",
    GS: "+500",
    GU: "+1-671",
    HK: "+852",
    HM: "+672",
    IM: "+44-1624",
    IO: "+246",
    JE: "+44-1534",
    MF: "+590",
    MO: "+853",
    MP: "+1-670",
    MQ: "+596",
    MS: "+1-664",
    NC: "+687",
    NF: "+672",
    NU: "+683",
    PF: "+689",
    PM: "+508",
    PN: "+64",
    PR: "+1-787",
    PS: "+970",
    RE: "+262",
    SH: "+290",
    SJ: "+47",
    SS: "+211",
    SX: "+1-721",
    TC: "+1-649",
    TF: "+262",
    TK: "+690",
    UM: "+1",
    VG: "+1-284",
    VI: "+1-340",
    WF: "+681",
    YT: "+262",
  };

  /*******************************************************
   * 4. NOTIFICATION SYSTEM (toast or overlay)
   *******************************************************/
  const showNotification = (message, type = "success", style = "toast") => {
    if (style === "toast") {
      // Using .fxc-notification.toast in the DOM
      const notification = $(".fxc-notification.toast");
      if (!notification.length) {
        console.error("Notification element not found in the DOM.");
        return;
      }
      notification
        .removeClass("success error active")
        .addClass(`${type} active`)
        .find(".notification-message")
        .text(message);

      notification.fadeIn(300);
      setTimeout(() => {
        notification.fadeOut(300, () => {
          notification.removeClass("active");
        });
      }, 5000);
    } else if (style === "overlay") {
      // Using .success-message-trigger, .fxc-form-page-inner-wrapper
      const $successTrigger = $(".success-message-trigger, .fxc-form-page-inner-wrapper");
      $successTrigger
        .removeClass("success error active-success-trigger")
        .addClass(type);

      if (type === "success") {
        $successTrigger.addClass("active-success-trigger");
      }
      $successTrigger.fadeIn(300);
    }
  };

/*******************************************************
 * 5. ERROR HANDLING (show/clear) for form fields
 *******************************************************/


  /*******************************************************
   * 6. SELECT2 FOR #country (Optional usage)
   *******************************************************/
  function initializeSelect2() {
    $("#country").select2({
      theme: "bootstrap",
      templateResult: formatCountryOption,
      templateSelection: formatCountrySelection,
      placeholder: "Select Country",
      allowClear: false,
    });
  }

  function formatCountryOption(country) {
    if (!country.id) {
      return country.text;
    }
    const flagUrl = $(country.element).data("flag");
    if (!flagUrl) {
      return country.text;
    }
    return $(`<span><img src="${flagUrl}" class="select2-country-flag" /> ${country.text}</span>`);
  }

  function formatCountrySelection(country) {
    if (!country.id) {
      return country.text;
    }
    const flagUrl = $(country.element).data("flag");
    if (!flagUrl) {
      return country.text;
    }
    return $(`<span><img src="${flagUrl}" class="select2-country-flag" /> ${country.text}</span>`);
  }

  // Initialize Select2
  initializeSelect2();

  /*******************************************************
   * 7. DEBOUNCED EMAIL CHECK FOR FDB FORM
   *    - We've moved this to the FDB form-specific scope
   *    - See the FDB-FORM-SPECIFIC LOGIC section below
   *******************************************************/

  // Event handler for the custom event in FDB form
  $(document).on("validate-fdb-email", function(e, $form, emailVal) {
    // This will trigger the debounceEmailCheck function in the FDB form scope
    console.log("Custom email validation event triggered:", emailVal);
  });

  /*******************************************************
   * 8. GENERIC .fxc-form VALIDATION (on Submit)
   *******************************************************/
 /*******************************************************
   * 8. GENERIC .fxc-form VALIDATION (on Submit)
   *******************************************************/
  function validateFormOnSubmit($form) {
    let isValid = true;

    // Validate First Name
    const $firstNameField = $form.find("#first_name");
    if ($firstNameField.length) {
      const firstNameVal = $firstNameField.val().trim();
      if (!firstNameVal) {
        showFieldError($form, "first_name", t("requiredField"));
        isValid = false;
      } else if (!validateName(firstNameVal)) {
        showFieldError($form, "first_name", t("invalidName"));
        isValid = false;
      }
    }

    // Validate Last Name
    const $lastNameField = $form.find("#last_name");
    if ($lastNameField.length) {
      const lastNameVal = $lastNameField.val().trim();
      if (!lastNameVal) {
        showFieldError($form, "last_name", t("requiredField"));
        isValid = false;
      } else if (!validateName(lastNameVal)) {
        showFieldError($form, "last_name", t("invalidName"));
        isValid = false;
      }
    }

    // Validate Email
    const $emailField = $form.find("#email");
    if ($emailField.length) {
      const emailVal = $emailField.val().trim();
      if (!emailVal) {
        showFieldError($form, "email", t("requiredField"));
        isValid = false;
      } else if (!validateEmail(emailVal)) {
        showFieldError($form, "email", t("invalidEmail"));
        isValid = false;
      }
    }

    // Validate Phone Number
    const $phoneField = $form.find("#phone_number");
    if ($phoneField.length) {
      const phoneVal = $phoneField.val().trim();
      if (!phoneVal) {
        showFieldError($form, "phone_number", t("requiredField"));
        isValid = false;
      } else if (!validatePhone(phoneVal)) {
        showFieldError($form, "phone_number", t("invalidPhone"));
        isValid = false;
      }
    }

    // Validate Country (select)
    const $countryField = $form.find("#country");
    if ($countryField.length) {
      const countryVal = $countryField.val().trim();
      if (!countryVal) {
        showFieldError($form, "country", t("requiredField"));
        isValid = false;
      }
    }

    // Validate Terms & Conditions
    const $termsField = $form.find("input[name='terms_conditions']");
    if ($termsField.length) {
      const termsChecked = $termsField.is(":checked");
      if (!termsChecked) {
        showFieldError($form, "terms_conditions", t("requiredTerms"));
        isValid = false;
      }
    }

    // Validate Account Type (radio)
    const $accountTypeField = $form.find("input[name='account_type']");
    if ($accountTypeField.length) {
      const accountTypeChecked = $form.find("input[name='account_type']:checked").length;
      if (!accountTypeChecked) {
        showFieldError($form, "account_type", t("accountTypeRequired"));
        isValid = false;
      }
    }

    // Conditional fields based on account type
    const accountType = $form.find("input[name='account_type']:checked").val();
    if (accountType === "individual") {
      const $idNumberField = $form.find("#id_number");
      if ($idNumberField.length) {
        const idNumberVal = $idNumberField.val().trim();
        if (!idNumberVal) {
          showFieldError($form, "id_number", t("requiredField"));
          isValid = false;
        } else if (!validateName(idNumberVal)) {
          showFieldError($form, "id_number", t("invalidIDNumber"));
          isValid = false;
        }
      }
    } else if (accountType === "corporate") {
      const $companyNameField = $form.find("#company_name");
      const $companyRegField = $form.find("#company_reg_number");

      if ($companyNameField.length) {
        const companyNameVal = $companyNameField.val().trim();
        if (!companyNameVal) {
          showFieldError($form, "company_name", t("requiredField"));
          isValid = false;
        }
      }
      if ($companyRegField.length) {
        const companyRegVal = $companyRegField.val().trim();
        if (!companyRegVal) {
          showFieldError($form, "company_reg_number", t("requiredField"));
          isValid = false;
        }
      }
    }

    // Validate Service Level (dropdown)
    const $serviceLevelField = $form.find("#service_level");
    if ($serviceLevelField.length) {
      const serviceLevelVal = $serviceLevelField.val().trim();
      if (!serviceLevelVal) {
        showFieldError($form, "service_level", t("requiredField"));
        isValid = false;
      } else {
        // If premium, check premium_option
        if (serviceLevelVal === "premium") {
          const $premiumOptionField = $form.find("#premium_option");
          if ($premiumOptionField.length) {
            const premiumOptionVal = $premiumOptionField.val().trim();
            if (!premiumOptionVal) {
              showFieldError($form, "premium_option", t("requiredField"));
              isValid = false;
            }
          }
        } else if (serviceLevelVal === "basic") {
          const $basicOptionField = $form.find("#basic_option");
          if ($basicOptionField.length) {
            const basicOptionVal = $basicOptionField.val().trim();
            if (!basicOptionVal) {
              showFieldError($form, "basic_option", t("requiredField"));
              isValid = false;
            }
          }
        }
      }
    }

    // Number of Followers
    const $followersField = $form.find("#number_of_followers");
    if ($followersField.length) {
      const followersVal = $followersField.val().trim();
      if (!followersVal) {
        showFieldError($form, "number_of_followers", t("requiredField"));
        isValid = false;
      } else {
        if (isNaN(followersVal)) {
          showFieldError($form, "number_of_followers", t("invalidNumber"));
          isValid = false;
        } else if (Number(followersVal) < 0) {
          showFieldError($form, "number_of_followers", t("nonNegativeNumber"));
          isValid = false;
        }
      }
    }

    // Link (URL)
    const $linkField = $form.find("#link");
    if ($linkField.length) {
      const linkVal = $linkField.val().trim();
      if (!linkVal) {
        showFieldError($form, "link", t("requiredField"));
        isValid = false;
      } else {
        const urlRegex = /^(https?:\/\/)?[\w.-]+\.[a-zA-Z]{2,}(\/\S*)?$/;
        if (!urlRegex.test(linkVal)) {
          showFieldError($form, "link", t("invalidURL"));
          isValid = false;
        }
      }
    }

    // FDB-Specific fields
    const $fdbEmail = $form.find("#fdb_email");
    if ($fdbEmail.length) {
      const emailVal = $fdbEmail.val().trim();
      if (!emailVal) {
        showFieldError($form, "fdb_email", t("emailRequired"));
        isValid = false;
      } else {
        const emailRegex = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$/;
        if (!emailRegex.test(emailVal)) {
          showFieldError($form, "fdb_email", t("invalidEmail"));
          isValid = false;
        }
      }
    }

    const $fdbCountry = $form.find("#fdb_country");
    if ($fdbCountry.length) {
      const countryVal = $fdbCountry.val().trim();
      if (!countryVal) {
        showFieldError($form, "fdb_country", t("requiredField"));
        isValid = false;
      }
    }

    const $platform = $form.find("#trading_platform");
    if ($platform.length) {
      const platformVal = $platform.val().trim();
      if (!platformVal) {
        showFieldError($form, "trading_platform", t("selectTradingPlatform"));
        isValid = false;
      }
    }

    const $mqidNumber = $form.find("#mqid_number");
    if ($mqidNumber.length) {
      const mqidNumberVal = $mqidNumber.val().trim();
      if (!mqidNumberVal) {
        showFieldError($form, "mqid_number", t("pleaseEnterMQID"));
        isValid = false;
      }
    }

    const $address = $form.find("#address");
    if ($address.length) {
      const addressVal = $address.val().trim();
      if (!addressVal) {
        showFieldError($form, "address", t("pleaseEnterAddress"));
        isValid = false;
      }
    }

    const $mtAccount = $form.find("#mt_account_number");
    if ($mtAccount.length) {
      const accountVal = $mtAccount.val().trim();
      if (!accountVal) {
        showFieldError($form, "mt_account_number", t("tradingAccountRequired"));
        isValid = false;
      }
      // If numeric only:
      /*
      else if (!/^\d+$/.test(accountVal)) {
        showFieldError($form, "mt_account_number", t("numericAccountOnly"));
        isValid = false;
      }
      */
    }

    return isValid;
  }

/**
   * Check for honeypot/spam submission
   */
  function checkHoneypot($form) {
    const $honeypot = $form.find('input[name="hp_protection"]');
    if ($honeypot.length) {
      const hpValue = $honeypot.val();
      if (hpValue || $honeypot.is(":visible") || $honeypot.css("opacity") !== "0") {
        showNotification(t("invalidSubmission"), "error", "toast");
        console.log("Honeypot triggered: possible bot submission");
        return true;
      }
    }
    return false;
  }

  /**
   * Inline validation on input/change, but only if a form submission
   * has already been attempted.
   */
  $(document).on("input change", ".fxc-form, .fxc-fdb-form", function (e) {
    if (!submissionAttempted) return;

    const $form = $(this);
    const target = e.target;
    const fieldName = target.name;
    const val = $(target).val();

    if (fieldName) {
      clearFieldError($form, fieldName);
    }

    // Simple real-time checks for a few fields
    switch (target.id) {
      case "first_name":
        if (!val) {
          clearFieldError($form, "first_name");
        } else if (!validateName(val)) {
          showFieldError($form, "first_name", t("invalidName"));
        } else {
          clearFieldError($form, "first_name");
        }
        break;

      case "last_name":
        if (!val) {
          clearFieldError($form, "last_name");
        } else if (!validateName(val)) {
          showFieldError($form, "last_name", t("invalidName"));
        } else {
          clearFieldError($form, "last_name");
        }
        break;

      case "email":
        if (!val) {
          clearFieldError($form, "email");
        } else if (!validateEmail(val)) {
          showFieldError($form, "email", t("invalidEmail"));
        } else {
          clearFieldError($form, "email");
        }
        break;

      case "phone_number":
        if (!val) {
          clearFieldError($form, "phone_number");
        } else if (!validatePhone(val)) {
          showFieldError($form, "phone_number", t("invalidPhone"));
        } else {
          clearFieldError($form, "phone_number");
        }
        break;

      default:
        break;
    }
  });

  /*******************************************************
   * 9. FORM SUBMISSION
   *******************************************************/
  $(document).on("submit", ".fxc-form", function (e) {
    e.preventDefault();
    const $form = $(this);

    if (checkHoneypot($form)) {
      return false;
    }

    submissionAttempted = true;

    if (!validateFormOnSubmit($form)) {
      return;
    }

    // Another honeypot check
    if ($form.find(".hp-field").length) {
      const hpValue = $form.find(".hp-field").val();
      if (hpValue) {
        showNotification(t("spamDetected"), "error", "toast");
        return;
      }
    }

    const formData = new FormData($form[0]);

    // reCAPTCHA v3 (optional)
    if (typeof grecaptcha !== "undefined") {
      grecaptcha.ready(function () {
        grecaptcha.execute("YOUR_RECAPTCHA_SITE_KEY", { action: "submit" }).then(function (token) {
          formData.append("recaptcha_response", token);
          finalizeFormSubmission($form, formData);
        });
      });
    } else {
      finalizeFormSubmission($form, formData);
    }
  });

  // .fxc-fdb-form
  $(document).on("submit", ".fxc-fdb-form", function (e) {
    e.preventDefault();
    submissionAttempted = true;
    const $form = $(this);

    if (checkHoneypot($form)) {
      return false;
    }

    if (!validateFormOnSubmit($form)) {
      return;
    }

    if ($form.find(".hp-field").length) {
      const hpValue = $form.find(".hp-field").val();
      if (hpValue) {
        showNotification(t("spamDetected"), "error", "toast");
        return;
      }
    }

    const formData = new FormData($form[0]);

    if (typeof grecaptcha !== "undefined") {
      grecaptcha.ready(function () {
        grecaptcha.execute("YOUR_RECAPTCHA_SITE_KEY", { action: "submit" }).then(function (token) {
          formData.append("recaptcha_response", token);
          finalizeFormSubmission($form, formData);
        });
      });
    } else {
      finalizeFormSubmission($form, formData);
    }
  });

  function handleGeneralError($form, message) {
    showNotification(message, "error", "toast");
    $form.find("button[type=submit]").prop("disabled", false).text("Submit");
  }

  function finalizeFormSubmission($form, formData) {
    // If you want the selected text from #country
    const selectedCountryText = $("#country option:selected").text().trim();
    formData.set("country", selectedCountryText);

    // Add required form data
    formData.append("action", "submit_customer_form");
    formData.append("security", fxc_ajax.nonce);

    const $submitButton = $form.find("button[type=submit]");
    const originalButtonText = $submitButton.text();

    $.ajax({
      type: "POST",
      url: fxc_ajax.ajax_url,
      data: formData,
      processData: false,
      contentType: false,
      timeout: 30000,

      beforeSend: function () {
        $submitButton.prop("disabled", true).text("Processing...");
      },

      success: function (response) {
        if (!response) {
          handleGeneralError($form, t("invalidServerResponse"));
          return;
        }

        try {
          if (response.success) {
            const style = response.data?.notification_style || "toast";
            const message = response.data?.message || t("formSubmittedSuccessfully");

            showNotification(message, "success", style);

            if (response.data?.redirect_url) {
              window.location.href = response.data.redirect_url;
              return;
            }

            // Reset form
            $form[0].reset();
            $form.find("#country").val(null).trigger("change");
            $form.find(".conditional-group").hide();
            $form.find("#selected_country_code").text("+1");
            $form.find("#full_phone").val("");
            $form.find(".phone-input-container").removeClass("error");
            $form.find(".error-field").removeClass("error-field");
            $form.find("span.error-message").removeClass("active").text("");
          } else {
            handleGeneralError($form, response.data?.message || t("anErrorOccurred"));
            if (response.data?.errors) {
              Object.keys(response.data.errors).forEach((field) => {
                showFieldError($form, field, response.data.errors[field]);
              });
            }
          }
        } catch (e) {
          console.error("Error processing form response:", e);
          handleGeneralError($form, t("invalidServerResponse"));
        }
      },

      error: function (xhr, status, error) {
        let errorMessage = t("anErrorOccurred");

        switch (status) {
          case "timeout":
            errorMessage = t("requestTimedOut");
            break;
          case "abort":
            errorMessage = "Request was cancelled. Please try again.";
            break;
          case "error":
            if (!navigator.onLine) {
              errorMessage = t("noInternet");
            } else if (xhr.status === 403) {
              errorMessage = t("accessDenied");
            } else if (xhr.status === 404) {
              errorMessage = t("endpointNotFound");
            } else if (xhr.status >= 500) {
              errorMessage = t("serverError");
            }
            break;
        }

        handleGeneralError($form, errorMessage);
        console.error("Form submission error:", {
          status: status,
          statusCode: xhr.status,
          statusText: xhr.statusText,
          error: error,
          response: xhr.responseText,
        });
      },

      complete: function () {
        $submitButton.prop("disabled", false).text(originalButtonText);
      },
    });
  }

  /*******************************************************
   * 11. COUNTRY & PHONE AUTO-DETECTION (OPTIONAL)
   *******************************************************/
  function initializeCountryDetection() {
    const $countryEl = $("#country");
    const $countryCodeSpan = $("#selected_country_code");
    const $phoneInput = $("#phone_number");

    function updateFullPhone() {
      if ($phoneInput.length) {
        const code = $countryCodeSpan.length ? $countryCodeSpan.text() : "00";
        const userNumber = $phoneInput.val() ? $phoneInput.val().trim() : "";
        if ($("#full_phone").length) {
          $("#full_phone").val(code + userNumber);
        }
      }
    }

    $countryEl.on("change", function () {
      const isoVal = $(this).val() || "US";
      if (flags[isoVal]) {
        $countryCodeSpan.text(flags[isoVal]);
      } else {
        $countryCodeSpan.text("00");
      }
      updateFullPhone();
    });

    $phoneInput.on("input", updateFullPhone);

    function detectCountry() {
      // Try ipapi first
      $.ajax({
        url: "https://ipapi.co/json/",
        method: "GET",
        dataType: "json",
        success: function (data) {
          if (data && data.country_code) {
            const ccode = data.country_code.toUpperCase();
            if ($(`#country option[value="${ccode}"]`).length) {
              $countryEl.val(ccode).trigger("change");
            }
          } else {
            detectCountryFallback();
          }
        },
        error: function () {
          detectCountryFallback();
        },
      });
    }

    function detectCountryFallback() {
      $.ajax({
        url: "https://get.geojs.io/v1/ip/geo.json",
        method: "GET",
        dataType: "json",
        success: function (data) {
          if (data && data.country_code) {
            const ccode = data.country_code.toUpperCase();
            if ($(`#country option[value="${ccode}"]`).length) {
              $countryEl.val(ccode).trigger("change");
            }
          }
        },
      });
    }

    detectCountry();
  }
  initializeCountryDetection();

  /*******************************************************
   * 12. PHONE INPUT HOVER & FOCUS STYLES
   *******************************************************/
  $(".phone-input-container").hover(
    function () {
      $(this).addClass("hover");
    },
    function () {
      $(this).removeClass("hover");
    }
  );

  $(".phone-input-container input[type='tel']")
    .on("focus", function () {
      $(this).closest(".phone-input-container").addClass("focus");
    })
    .on("blur", function () {
      $(this).closest(".phone-input-container").removeClass("focus");
    });
});

/*******************************************************
 *  SECOND SCRIPT BLOCK: CUSTOM DROPDOWNS
 *******************************************************/
jQuery(document).ready(function ($) {
  function initializeCustomDropdowns() {
    $(".fxc-dropdown").each(function () {
      const $dropdown = $(this);
      const $selected = $dropdown.find(".fxc-dropdown-selected");
      const $options = $dropdown.find(".fxc-dropdown-options");

      $options.hide();

      const name = $dropdown.data("name");
      if (name) {
        const $input = $("<input>", {
          type: "hidden",
          name,
          value: $selected.data("value"),
        });
        $dropdown.append($input);
      }

      // Toggle on click
      $selected.on("click", function (e) {
        e.stopPropagation();

        $(".fxc-dropdown")
          .not($dropdown)
          .removeClass("active")
          .find(".fxc-dropdown-options")
          .slideUp(200);

        $dropdown.toggleClass("active");
        $options.slideToggle(200);
      });

      // Click on option
      $options.find(".fxc-dropdown-option").on("click", function () {
        const value = $(this).data("value");
        const text = $(this).text();

        $selected.empty().data("value", value).append(text).append('<span class="fxc-dropdown-arrow"></span>');
        $dropdown.find('input[type="hidden"]').val(value);

        $options.slideUp(200);
        $dropdown.removeClass("active");

        $dropdown.trigger("change");
      });

      // Outside click to close
      $(document).on("click", function () {
        $options.slideUp(200);
        $dropdown.removeClass("active");
      });
    });
  }

  function initializeConditionalFields() {
    $(".fxc-dropdown").on("change", function () {
      const $conditionalGroups = $(".conditional-group");
      $conditionalGroups.hide();

      const mapStr = $(this).attr("data-toggle-map");
      if (!mapStr) return;

      try {
        const map = JSON.parse(mapStr);
        const value = $(this).find(".fxc-dropdown-selected").data("value");
        if (map && map[value]) {
          $(`#${map[value]}`).show();
        }
      } catch (e) {
        console.error("Invalid JSON in data-toggle-map:", e);
      }
    });
  }

  initializeCustomDropdowns();
  initializeConditionalFields();
});

/*******************************************************
 *  FINAL SCRIPT BLOCK: FDB FORM-SPECIFIC LOGIC
 *******************************************************/
(function ($) {
  "use strict";

  $(document).ready(function () {
    const $fdbForm = $(".fxc-fdb-form");
    if (!$fdbForm.length) return;

    // Form elements
    const formElements = {
      firstName: $fdbForm.find("#first_name"),
      lastName: $fdbForm.find("#last_name"),
      email: $fdbForm.find("#fdb_email"),
      country: $fdbForm.find("#fdb_country"),
      submitBtn: $fdbForm.find("#fdb_submit_button")
    };

    let isValidClient = false; // New flag to track if email belongs to valid client

    // Error handling functions - use the global ones instead of redefining
    function safeTrim($el) {
      return $el && $el.length ? $el.val().trim() : "";
    }

    /**
     * Check if an email is already in ActiveCampaign with a specific tag
     * This function only runs for forms with check_email_in_ac enabled
     */
    function checkEmailInActiveCampaign(email) {
      return new Promise((resolve, reject) => {
        if (!email || !validateEmail(email)) {
          reject('Invalid email');
          return;
        }

        const formType = $fdbForm.find('[name="form_type"]').val();
        
        // Note: We don't add checking class here as it's already added in debounceEmailCheck
        console.log('Checking email in ActiveCampaign:', email);
        
        $.ajax({
          url: fxc_ajax.ajax_url,
          type: 'POST',
          data: {
            action: 'check_email_in_ac',
            email: email,
            form_type: formType,
            security: fxc_ajax.nonce
          },
          success: function(response) {
            // Display debug tree in console if available
            if (response.data && response.data.debug_tree) {
              displayAcDebugTree(response.data.debug_tree);
            }
            
            if (response.success) {
              // Email not found with this tag or check not enabled
              resolve(false);
            } else if (response.data && response.data.exists) {
              // Email exists with the tag, show message
              $("#fdb_email").addClass("error-field");
              $("#email_error")
                .html('<span class="email-exists">' + response.data.message + '</span>')
                .show()
                .addClass("error-message active");
              
              isValidClient = false;
              resolve(true);
            } else {
              // Other error
              $("#email_error").hide();
              resolve(false);
            }
          },
          error: function(xhr, status, error) {
            console.error('Error checking email in ActiveCampaign:', error);
            // On error, we'll proceed (fail open)
            $("#email_error").hide();
            reject(error);
          }
          // Note: No complete callback here as the finally block in debounceEmailCheck handles it
        });
      });
    }

    const debounceEmailCheck = debounce(function ($form, emailVal) {
      // Reset verification flags
      isValidClient = false;
      
      // Remove any previous success state
      formElements.email.closest('.email-input-wrapper').removeClass("checked-successful");
      
      if (!validateEmail(emailVal)) {
        showFieldError($form, "email", t("invalidEmail"));
        $("#fdbMessage").hide();
        validateFdbFields();
        return;
      }

      clearFieldError($form, "email");
      
      // Add checking animation to the wrapper element
      const $emailWrapper = formElements.email.closest('.email-input-wrapper');
      $emailWrapper.addClass("email-checking");
      
      // Disable submit button during check
      formElements.submitBtn.prop("disabled", true);

      const ajaxData = {
        action: "fetch_country",
        email: emailVal,
        security: fxc_ajax.nonce
      };

      $.ajax({
        type: "POST",
        url: fxc_ajax.ajax_url,
        data: ajaxData,
        success: function (response) {
          if (response.success && response.data.country) {
            const country = response.data.country;
            const eligibleCountries = ["Indonesia", "Pakistan", "United Arab Emirates"];
            const restrictedCountries = ["Bangladesh", "Turkey"];

            formElements.country.val(country);
            
            // Check if form has bypass-restriction attribute
            const bypassRestriction = $form.attr('data-bpr') === 'true';

            if (restrictedCountries.includes(country) && !bypassRestriction) {
              const msg = `First Deposit Bonus (FDB) is not available to clients from ${country}.`;
              $("#fdbMessage")
                .addClass("restricted-countries")
                .removeClass("not-restricted-countries")
                .html(msg)
                .show();
              isValidClient = false;
              
              // Remove checking class
              $emailWrapper.removeClass("email-checking");
              validateFdbFields();
            } else {
              if (eligibleCountries.includes(country)) {
                const msg = `Clients from ${country} are eligible for a maximum FDB of up to 20% of their initial deposit.`;
                $("#fdbMessage")
                  .addClass("not-restricted-countries")
                  .removeClass("restricted-countries")
                  .html(msg)
                  .show();
              } else {
                $("#fdbMessage").hide();
              }
              
              // Country check passed
              isValidClient = true;
              
              // Now check if email is already in AC list
              checkEmailInActiveCampaign(emailVal)
                .then(emailExists => {
                  if (emailExists) {
                    // AC check failed - email already exists
                    isValidClient = false;
                  } else {
                    // Both checks passed - add success class
                    if (isValidClient) {
                      console.log("Adding checked-successful class");
                      $emailWrapper.addClass("checked-successful");
                    }
                  }
                })
                .catch(error => {
                  console.error("Error checking email in AC:", error);
                  isValidClient = false;
                })
                .finally(() => {
                  // Always remove checking class and validate fields when all checks are complete
                  $emailWrapper.removeClass("email-checking");
                  validateFdbFields();
                  
                  // Final validation for success class - must be valid after all checks
                  if (!isValidClient) {
                    $emailWrapper.removeClass("checked-successful");
                  }
                });
            }
          } else {
            showFieldError($form, "email", "Client account not found.");
            formElements.country.val("");
            isValidClient = false;
            $("#fdbMessage").hide();
            // Remove checking class
            $emailWrapper.removeClass("email-checking");
            validateFdbFields();
          }
        },
        error: function () {
          showFieldError($form, "email", t("anErrorOccurred"));
          formElements.country.val("");
          isValidClient = false;
          $("#fdbMessage").hide();
          // Remove checking class
          $emailWrapper.removeClass("email-checking");
          validateFdbFields();
        }
      });
    }, 500);

    // Custom event handler for FDB email validation
    $(document).on("validate-fdb-email", function(e, $form, emailVal) {
      debounceEmailCheck($form, emailVal);
    });
    
    // Field event handlers
    formElements.firstName.on("input", function() {
      const value = safeTrim($(this));
      clearFieldError($fdbForm, "first_name");
      
      if (value === "") {
        showFieldError($fdbForm, "first_name", t("firstNameRequired"));
      } else if (!validateName(value)) {
        showFieldError($fdbForm, "first_name", t("firstNameLettersOnly"));
      }
      validateFdbFields();
    });

    formElements.lastName.on("input", function() {
      const value = safeTrim($(this));
      clearFieldError($fdbForm, "last_name");
      
      if (value === "") {
        showFieldError($fdbForm, "last_name", t("lastNameRequired"));
      } else if (!validateName(value)) {
        showFieldError($fdbForm, "last_name", t("lastNameLettersOnly"));
      }
      validateFdbFields();
    });

    formElements.email.on("input", function() {
      const emailVal = safeTrim($(this));
      clearFieldError($fdbForm, "email");
      
      if (emailVal === "") {
        showFieldError($fdbForm, "email", t("emailRequired"));
        $("#fdbMessage").hide();
        isValidClient = false;
      } else if (!validateEmail(emailVal)) {
        showFieldError($fdbForm, "email", t("invalidEmail"));
        $("#fdbMessage").hide();
        isValidClient = false;
      } else {
        // Directly use debounceEmailCheck inside the FDB form scope
        debounceEmailCheck($fdbForm, emailVal);
      }
      validateFdbFields();
    });

    function validateFdbFields() {
      const firstName = safeTrim(formElements.firstName);
      const lastName = safeTrim(formElements.lastName);

      const nameValid = firstName !== "" && validateName(firstName);
      const lastValid = lastName !== "" && validateName(lastName);

      const hasErrors = $fdbForm.find(".error-message.active").length > 0;
      // Check if the wrapper has the checking class, not the input itself
      const isChecking = formElements.email.closest('.email-input-wrapper').hasClass("email-checking");
      
      formElements.submitBtn.prop("disabled", 
        !(nameValid && lastValid && isValidClient) || hasErrors || isChecking
      );
    }

    // Initial validation
    validateFdbFields();
  });
})(jQuery);

/*******************************************************
 * AC Debug Tree Formatter
 * 
 * Add this to your forms.js file to handle the debug tree coming
 * from the AJAX response.
 */
function displayAcDebugTree(debugTree) {
  if (!debugTree || debugTree.length === 0) {
    return;
  }
  
  console.groupCollapsed('🔍 ActiveCampaign Tag Check Process');
  
  debugTree.forEach(function(log, index) {
    const icon = getAcDebugIcon(log.level);
    const timeStr = log.timestamp.split(' ')[1];
    
    if (log.level === 'error') {
      console.error(`${icon} [${timeStr}] ${log.message}`);
    } else if (log.level === 'warning') {
      console.warn(`${icon} [${timeStr}] ${log.message}`);
    } else if (log.level === 'success') {
      console.log(`%c${icon} [${timeStr}] ${log.message}`, 'color: green; font-weight: bold');
    } else {
      console.log(`${icon} [${timeStr}] ${log.message}`);
    }
    
    // If this is the last log, add a summary
    if (index === debugTree.length - 1) {
      console.log('%c✓ Check process completed', 'color: blue; font-weight: bold');
    }
  });
  
  console.groupEnd();
}

function getAcDebugIcon(level) {
  switch(level) {
    case 'error': return '❌';
    case 'warning': return '⚠️';
    case 'success': return '✅';
    case 'info':
    default: return 'ℹ️';
  }
}
/*******************************************************
 * END OF FXC-FORMS MASTER JS
 * ****************************************************/