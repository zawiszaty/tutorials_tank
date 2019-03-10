import React from 'react';
import PropTypes from 'prop-types';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import FormControl from '@material-ui/core/FormControl';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import Checkbox from '@material-ui/core/Checkbox';
import Input from '@material-ui/core/Input';
import InputLabel from '@material-ui/core/InputLabel';
import LockIcon from '@material-ui/icons/LockOutlined';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import withStyles from '@material-ui/core/styles/withStyles';
import ListItem from "@material-ui/core/ListItem";
import ListItemText from "@material-ui/core/ListItemText";

const styles = theme => ({
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: '80%',
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    avatar: {
        margin: theme.spacing.unit,
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

function Regulamin(props) {
    const {classes} = props;

    return (
        <main className={classes.main}>
            <CssBaseline/>
            <Paper className={classes.paper}>
                <Typography component="h1" variant="headline">
                    Regulamin
                </Typography>
                <ListItem>
                    <ListItemText
                        primary="1. Administratorem danych osobowych jest Zespół Tutorials Tank"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="2. Administrator wyznaczył inspektora ochrony danych osobowych. Dane kontaktowe inspektora: szymonciom@gmail.com"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="3. Przekazane dane osobowe przetwarzane będą w celu realizacji usług, obsługi zgłoszeń i udzielania odpowiedzi na zgłoszenia"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="4. Kategorie danych osobowych obejmują m.in. imię i nazwisko, numer telefonu, adres e-mail, adres, dane
                dedykowane do procesu/usługi/projektu;"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="5. Pani / Pana dane osobowe mogą być przekazywane podmiotom przetwarzającym dane osobowe na zlecenie
                administratora: dostawcy usług IT"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="6. Państwa dane osobowe będą przechowywane przez okres istnienia prawnie uzasadnionego interesu
                administratora, chyba że Pani / Pan wyrazi sprzeciw wobec przetwarzania danych;"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="7. Państwa dane nie będą przekazywane do państwa trzeciego ani organizacji międzynarodowej;"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="8. Posiadają Państwo prawo dostępu do treści swoich danych oraz prawo ich sprostowania, usunięcia,
                ograniczenia przetwarzania, prawo do przenoszenia danych, prawo wniesienia sprzeciwu, prawo do cofnięcia
                zgody w dowolnym momencie bez wpływu na zgodność z prawem przetwarzania, którego dokonano na podstawie
                zgody przed jej cofnięciem;"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="9. Mają Państwo prawo wniesienia skargi do organu nadzorczego zajmującego się ochroną danych osobowych,
                którym jest Prezes Urzędu Ochrony Danych Osobowych, gdy uznają Państwo, iż przetwarzanie Państwa danych
                osobowych narusza przepisy ustawy z dnia 10 maja 2018 r. o ochronie danych osobowych (tekst jednolity
                Dz. U. z 2018 r., poz. 1000) lub przepisy Rozporządzenia Parlamentu Europejskiego i Rady (UE) 2016/679 z
                dnia 27 kwietnia 2016 r. w sprawie ochrony osób fizycznych w związku z przetwarzaniem danych osobowych i
                w sprawie swobodnego przepływu takich danych oraz uchylenia dyrektywy 95/46/WE (ogólne rozporządzenie o
                ochronie danych) z dnia 27 kwietnia 2016 r. (Dz.Urz.UE.L Nr 119, str. 1);"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="10. Dane udostępnione przez Panią/Pana nie będą podlegały zautomatyzowanemu podejmowaniu decyzji oraz
                profilowaniu;"
                    />
                </ListItem>
                <ListItem>
                    <ListItemText
                        primary="11. Podanie przez Państwa danych osobowych jest dobrowolne;"
                    />
                </ListItem>
            </Paper>
        </main>
    );
}

Regulamin.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(Regulamin);