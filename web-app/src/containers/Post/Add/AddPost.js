import React, {Component} from 'react';
import PropTypes from 'prop-types';
import CssBaseline from '@material-ui/core/CssBaseline';
import Typography from '@material-ui/core/Typography';
import withStyles from '@material-ui/core/styles/withStyles';
import AppBar from "@material-ui/core/AppBar";
import Tabs from "@material-ui/core/Tabs";
import SwipeableViews from 'react-swipeable-views';
import Tab from '@material-ui/core/Tab';
import OtherSite from "./Type/OtherSite";
import OwnPost from "./Type/OwnPost";

function TabContainer({children, dir}) {
    return (
        <Typography component="div" dir={dir} style={{padding: 8 * 3}}>
            {children}
        </Typography>
    );
}

const styles = theme => ({
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: "80%",
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
    root: {
        backgroundColor: theme.palette.background.paper,
        width: 500,
    },
});

class AddPost extends Component {
    state = {
        value: 'one',
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
            <main className={classes.main}>
                <CssBaseline/>
                <AppBar position="static" color="default" className={classes.paper}>
                    <Tabs
                        value={this.state.value}
                        onChange={this.handleChange}
                        indicatorColor="primary"
                        textColor="primary"
                        variant="fullWidth"
                    >
                        <Tab value="one" label="Inna strona"/>
                        <Tab value="two" label="Własny Post"/>
                    </Tabs>
                </AppBar>
                <SwipeableViews
                    axis={theme.direction === 'rtl' ? 'x-reverse' : 'x'}
                    index={this.state.value}
                    onChangeIndex={this.handleChangeIndex}
                >
                    {this.state.value === 'one' ?
                        <TabContainer dir={theme.direction}>
                            <OtherSite/>
                        </TabContainer> : <TabContainer dir={theme.direction}><OwnPost/></TabContainer>}
                </SwipeableViews>
            </main>
        );
    }
}

AddPost.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(styles, {withTheme: true})(AddPost);