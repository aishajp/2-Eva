import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import SpotiMain from './SpotiMain.jsx'
import './index.css'

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <SpotiMain />
  </StrictMode>,
)
