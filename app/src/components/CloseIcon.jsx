import * as React from "react";

function IconClose(props) {
  return (
    <svg
      viewBox="0 0 512 512"
      fill="currentColor"
      height="3em"
      width="3em"
      {...props}
    >
      <path
        fill="none"
        stroke="#fff"
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={32}
        d="M368 368L144 144M368 144L144 368"
      />
    </svg>
  );
}

export default IconClose;