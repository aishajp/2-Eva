import { BrowserRouter} from 'react-router-dom'
import {ExpresionesProvider} from './functions/RegularExpressions';
import VisualizarUsuario from './pages/VisualizarUsuario'

export default function Welcome() {
  console.log('Nos fuimo a su\'pendel mi Loco')
  return (
  <BrowserRouter>
    <ExpresionesProvider>
      <VisualizarUsuario/>
    </ExpresionesProvider>
  </BrowserRouter>
  )
}