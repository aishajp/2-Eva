import { createContext, useState } from "react";

export const expresionesContext = createContext();

export const ExpresionesProvider = ({ children }) => {
    const [expresiones, setExpresiones] = useState({
        username1:/(^[a-z])/,
        username2:/(?=.*\d)/,
        username3:/[a-z0-9]{5,}/,
        password1: /(?=.*[a-z])/,
        password2: /(?=.*[A-Z])/,
        password3: /(?=.*\d)/,
        password4: /^.{8,}$/,
    });
    return (
        <expresionesContext.Provider value={{ expressions: expresiones, setExpresiones }}>
            {children}
        </expresionesContext.Provider>
    );
}