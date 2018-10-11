import React from 'react';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../../../axios';
import EditCategoryForm from './EditCategoryForm';
import CssBaseline from "@material-ui/core/CssBaseline/CssBaseline";
import Paper from "@material-ui/core/Paper/Paper";
import Avatar from "@material-ui/core/Avatar/Avatar";
import LockIcon from "@material-ui/core/SvgIcon/SvgIcon";
import Typography from "@material-ui/core/Typography/Typography";
import AddCategoryForm from "../Add/AddCategoryForm";
import {connect} from 'react-redux';
import {getCategory} from "../../../../actions/category-action";

const styles = theme => ({
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
});

class EditCategory extends React.Component {
    constructor(props) {
        super(props);
        const {match} = this.props;

        this.state = {
            category: {},
            id: match.params.id,
            loading: true,
        };

        this.getCategory = this.getCategory.bind(this);
    }

    componentDidMount() {
        this.getCategory();
    }

    getCategory() {
        this.setState({
            loading: true
        });

        axios.get(`/api/category/${this.state.id}`)
            .then((response) => {
                this.props.getCategory(response.data);
                this.setState({
                    loading: false,
                })
            })
    }

    render() {
        const classes = this.props.classes;
        let formView;
        if (this.state.loading === true) {
            formView = 'loading';
        } else {
            formView = <EditCategoryForm id={this.props.category.id}/>;
        }
        return (
            <React.Fragment>
                <CssBaseline/>
                <main className={classes.layout}>
                    <Paper className={classes.paper}>
                        <Avatar className={classes.avatar}>
                            <LockIcon/>
                        </Avatar>
                        <Typography variant="headline">Dodaj Kategorie</Typography>
                        {formView}
                    </Paper>
                </main>
            </React.Fragment>
        );
    }
}

const mapStateToProps = state => ({
    category: state.category
});

const mapActionToProps = {
    getCategory: getCategory
};


export default connect(mapStateToProps, mapActionToProps)(withStyles(styles)(EditCategory));