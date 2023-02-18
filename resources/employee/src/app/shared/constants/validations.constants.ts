export const customValidations = {
  maxLength: 25,
  zipCodeRegex: /^([0-9]){5,9}$/,
  descriptionRegex: /^[^\s]+[#.!@0-9a-zA-Z\s,'-`$_;"|]+$/,
  // rosterRegex: /^([A-Za-z 0-9\)\()]){1,25}$/,
  numberAndNameRegex: /^(?=.{1,30}$)[A-Za-z0-9\)\()]+(?:['-_.\s][A-Za-z0-9\)\()]+)*$/,
  numberAndNameRegexWithoutLimit: /^(?=.{1,}$)[A-Za-z0-9\)\()]+(?:['-_.\s][A-Za-z0-9\)\()]+)*$/,
  nameRegex: /^(?=.{1,30}$)[A-Za-z]+(?:['-_.\s][A-Za-z]+)*$/,
  nameRegexWithoutLimit: /^(?=.{1,}$)[A-Za-z]+(?:['-_.\s][A-Za-z]+)*$/,
  numberRegex: /^(\d+){1,15}$/,
  email: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
  passwordRegex: /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{6,30}$/,
  alphaNumeric: /[a-z\d\-+_\s]/,
  nameRegexWithSpace: /^[A-Za-z\s\.-]*$/
}