const baseTokens = {
  "#": { pattern: /\d/ },
  "?": { pattern: /[a-zA-Z]/ },
  _: { pattern: /[0-9a-zA-Z]/ },
  "!": { escape: true },
};

export default function mask(value: string, mask: string, masked = true) {
  value = value || "";
  mask = mask || "";
  var iMask = 0;
  var iValue = 0;
  var output = "";
  while (iMask < mask.length && iValue < value.length) {
    var cMask = mask[iMask] as keyof typeof baseTokens;
    var masker = baseTokens[cMask];
    var cValue = value[iValue];
    if (masker && !(masker as typeof baseTokens["!"]).escape) {
      if ((masker as typeof baseTokens["#"]).pattern.test(cValue)) {
        output += cValue;
        iMask++;
      }
      iValue++;
    } else {
      if (masker && (masker as typeof baseTokens["!"]).escape) {
        iMask++;
        cMask = mask[iMask] as keyof typeof baseTokens;
      }
      if (masked) output += cMask;
      if (cValue === cMask) iValue++;
      iMask++;
    }
  }

  var restOutput = "";
  while (iMask < mask.length && masked) {
    var cMask = mask[iMask] as keyof typeof baseTokens;
    if (baseTokens[cMask]) {
      restOutput = "";
      break;
    }
    restOutput += cMask;
    iMask++;
  }

  return output + restOutput;
}
