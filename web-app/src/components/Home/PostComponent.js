import React, {Component} from 'react';
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
import AppBar from "@material-ui/core/AppBar";
import Tabs from "@material-ui/core/Tabs";
import SwipeableViews from 'react-swipeable-views';
import Tab from '@material-ui/core/Tab';
import Grid from "@material-ui/core/Grid";
import { Link as RouterLink } from 'react-router-dom'

function TabContainer({children, dir}) {
    return (
        <Typography component="div" dir={dir} style={{padding: 8 * 3}}>
            {children}
        </Typography>
    );
}

const styles = theme => ({
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    root: {
        backgroundColor: theme.palette.background.paper,
        width: 500,
    },
    button: {
        alignSelf: "flex-end"
    }
});

class AddPost extends Component {
    state = {
        value: 0,
    };

    handleChange = (event, value) => {
        this.setState({value});
    };

    handleChangeIndex = index => {
        this.setState({value: index});
    };

    render() {
        const {classes, theme} = this.props;

        return (
            <Paper className={classes.paper}>
                <CssBaseline/>
                <Grid container spacing={16}>
                    <Grid item xs={4}>
                        <img src={"http://localhost:9999" + this.props.thumbnail} alt="post thumbnail" width="100%"/>
                    </Grid>
                    <Grid item xs={8}>
                        <Typography variant="title" gutterBottom>
                            {this.props.title}
                        </Typography>
                        <Typography variant="display1" gutterBottom>
                            {this.props.shortDescription}
                        </Typography>
                        <Button
                            variant="text"
                            color="default"
                            fullWidth
                            component={RouterLink} to={"/post/" + this.props.slug}
                            className={classes.button}
                        >
                            Czytaj wiecej
                        </Button>
                    </Grid>
                </Grid>
            </Paper>
        );
    }
}

AddPost.propTypes = {
    classes: PropTypes.object.isRequired,
    thumbnail: PropTypes.object.isRequired,
    title: PropTypes.object.isRequired,
    shortDescription: PropTypes.object.isRequired,
    slug: PropTypes.object.isRequired,
};

export default withStyles(styles, {withTheme: true})(AddPost);